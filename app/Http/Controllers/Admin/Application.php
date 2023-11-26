<?php

namespace App\Http\Controllers\Admin;

use App\Facades\AuthAdminFacade;
use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\RefRequestTypeRepositoryInterface;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseAuthBasisRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseRepositoryInterface;
use App\Repositories\Interfaces\RequestAuthBasisRepositoryInterface;
use App\Facades\AuthFacade;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\LogAdminRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Rules\ValidMobile;
use App\Rules\ValidPersonNationalCode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Application extends Controller
{
    public function __construct()
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return redirect()->route('admin-login');
        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Shows login page
     */
    public function index()
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return redirect()->route('admin-login');
        }
        return view('admin.applications');
    }

    // ---------------------------------------------------------------------------------------------------

    public function getRecords(ApplicationRepositoryInterface $appRep, Request $request)
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return response()->apiRes(null, "", false, 401);
        }

        $conditions = [];

        // اگر شناسه نوع مدیر برابر یک است درخواستهای همه استان ها را برگردان در غیر اینصورت فقط درخواست های مربوط به استان کاربر را برگردان
        if(AuthAdminFacade::getAdminTypeId() == 1) { // نوع ادمین کارشناس کل است
            $conditions['province_id'] = NULL;
        } else { // نوع ادمین کارشناس استان است
            $conditions['province_id'] = AuthAdminFacade::getAdminProvinceId();
        }

        // Validation
        $validator = Validator::make($request->all(), [
            'length' => ['required', 'int'],
            'start' => ['required', 'int'],
            'draw' => ['required', 'int'],
            'application_id' => ['int'],
            'p_name' => ['string'],
            'p_lname' => ['string'],
            'person_type_title' => ['string'],
            'p_ncode' => [new ValidPersonNationalCode],
            'mobile' => [new ValidMobile]
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'draw' => 0, "recordsTotal" => 0, "recordsFiltered" => 0]);
        }

        $length = (int) $request->input('length'); // limit
        $start = (int) $request->input('start'); // offset
        $draw = (int) $request->input('draw'); // page

        $conditions['p_ncode'] = $request->input('p_ncode', null);
        $conditions['p_name'] = $request->input('p_name', null);
        $conditions['p_lname'] = $request->input('p_lname', null);
        $conditions['application_id'] = $request->input('application_id', null);
        $conditions['person_type_title'] = $request->input('person_type_title', null);
        $conditions['mobile'] = $request->input('mobile', null);
        $conditions['province_title'] = $request->input('province_title', null);
        $conditions['status_title'] = $request->input('status_title', null);
        $conditions['tracking_code'] = $request->input('tracking_code', null);


        $applic = $appRep->getApplications($length, $start, $conditions);

        $applications = Collection::make($applic['items'])->map(function ($application) {
            $application = json_decode(json_encode($application), true);
            return [
                $application['application_id'],
                $application['person_type_title'],
                $application['p_name'],
                $application['p_lname'],
                $application['p_ncode'],
                $application['user_mobile'],
                $application['province_title'],
                $application['status_title'],
                CalendarUtils::convertNumbers(Jalalian::forge(strtotime($application['application_id']))->format("Y-m-d H:i")),
                $application['tracking_code'],
                '<a target="_blank" href="/admin/application/'.$application['application_id'].'"><button class="btn btn-success">نمایش</button></a>'
            ];
        });

        $data = [
            'data' => $applications,
            'columns' => [
                ['title' =>  'شناسه'],
                ['title' =>  'نوع'],
                ['title' =>  'نام'],
                ['title' =>  'نام و نام خانوادگی'],
                ['title' =>  'کدملی'],
                ['title' =>  'شماره موبایل'],
                ['title' =>  'استان'],
                ['title' =>  'وضعیت'],
                ['title' =>  'تاریخ'],
                ['title' =>  'کد رهگیری'],
                ['title' =>  'عملیات']
            ],
            'count' => $applic['count']
        ];

        return response()->json($data);
    }

    // ---------------------------------------------------------------------------------------------------

    public function view(Request $request,
                         ApplicationRepositoryInterface $appRepo,
                         EducationRepositoryInterface $eduRep,
                         RequestAuthBasisRepositoryInterface $reqAuthRep,
                         PreviousLicenseRepositoryInterface $preLicRep,
                         PreviousLicenseAuthBasisRepositoryInterface $preLicAuthRep,
                         PaymentRepositoryInterface $payRepo,
                         LogAdminRepositoryInterface $logAdminRepo)
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return redirect()->route('admin-login');
        }

        $app_id = $request->application_id;
        $log = ['admin_id' => AuthAdminFacade::getAdminId(),'action' => 'view-application', 'ip' => $request->ip(), 'data' => $app_id];
        $logAdminRepo->create($log);
        $application = $appRepo->getApplicationFullDetails($app_id);
        $application->education_records = $eduRep->getByAppId($app_id);
        $application->request_auth_records = $reqAuthRep->getByAppId($app_id);
        $application->previous_license = $preLicRep->getByAppId($app_id);
        if( ! is_null($application->previous_license) ){
            $application->previous_license_auth_records = $preLicAuthRep->getByAppId($app_id);
            $images = $preLicRep->getImages($app_id);
            if($images['founded']) {
                $images['front'] = '/admin' . $images['front'];
                $images['rear'] = '/admin' . $images['rear'];
            }
            $application->previous_license_images = $images;
        } else {
            $application->previous_license_auth_records = [];
            $application->previous_license_images = [];
        }

        $payment = $payRepo->findGtpPayedRecordByApplication($application->application_id);

        // dd($application);
        return view('admin.application', ['app' => $application, 'pay' => $payment]);
    }

    // ---------------------------------------------------------------------------------------------------

    public function report(PaymentRepositoryInterface $payRepo, ApplicationRepositoryInterface $appRepo, RefRequestTypeRepositoryInterface $refReqRepo)
    {
        if(\App\Facades\AuthAdminFacade::getAdminTypeId() != 1){
            abort(403);
        }
        // Get number of payed applications for each request type
        $request_types = $refReqRepo->getAll();
        for($i=0; $i< count($request_types); $i++) {
            $request_types[$i]->count = $appRepo->countPayedApplicationsBasedOnRequestType($request_types[$i]->req_type_id);
        }

        // Get number of payed applications for each person type
        $person_types = [
            'payments_total' => $payRepo->countPayedApplications()->count,
            'payments_regular' => $payRepo->countPayedApplications(1)->count,
            'payments_legal' => $payRepo->countPayedApplications(2)->count,
        ];

        // Get number of payed applications for each province
        $provinces_regular = $appRepo->countPayedApplicationsBasedOnProvince(1); // 1 means regualr person
        $provinces_legal = $appRepo->countPayedApplicationsBasedOnProvince(2); // 1 means legal person

        $data = [
            'person_type' => $person_types,
            'request_types' => $request_types,
            'provinces_regular' => $provinces_regular,
            'provinces_legal' => $provinces_legal,
        ];

        return view('admin.report', $data);
    }

}
