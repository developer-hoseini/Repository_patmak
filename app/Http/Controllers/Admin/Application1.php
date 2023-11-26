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
use App\Rules\ValidPersonNationalCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Application extends Controller
{
    public function __construct()
    {        
//        if( ! AuthAdminFacade::isLoggedIn() ) {
//           return redirect()->route('admin-login');
//        }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Shows login page
     */
    public function index()
    {
//        if( ! AuthAdminFacade::isLoggedIn() ) {
//            return redirect()->route('admin-login');
//         }
        return view('admin.applications');
    }

    // ---------------------------------------------------------------------------------------------------

    public function getRecords(ApplicationRepositoryInterface $appRep, Request $request)
    {
//        if( ! AuthAdminFacade::isLoggedIn() ) {
//            return response()->apiRes(null, "", false, 401);
//         }

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
            'p_ncode' => [new ValidPersonNationalCode],
        ]);
        if ($validator->fails()) {
            return response()->json(['data' => [], 'draw' => 0, "recordsTotal" => 0, "recordsFiltered" => 0]);
        }

        $length = (int) $request->input('length'); // limit
        $start = (int) $request->input('start'); // offset
        $draw = (int) $request->input('draw'); // page

        $conditions['p_ncode'] = $request->input('p_ncode', null);        
        $conditions['mobile'] = $request->input('mobile', null);        

        list($total_records, $applications) = $appRep->getApplications($length, $start, $conditions);
        for($i=0; $i < count($applications); $i++){
            // Convert Georgian to Jalali date
            $created_at_jalali = Jalalian::forge(strtotime($applications[$i]->created_at))->format("Y-m-d H:i");
            // Convert numbers to persian characters
            $applications[$i]->created_at= CalendarUtils::convertNumbers($created_at_jalali);
            $applications[$i]->request_type = '<span class="badge status-pill rounded-pill bg-secondary p-2"  data-bs-toggle="tooltip" data-bs-placement="top" title="' . $applications[$i]->req_type_title . '"><span class="visually-hidden">New alerts</span></span>'; 
            $applications[$i]->actions= '<a target="_blank" href="/admin/application/'.$applications[$i]->application_id.'"><button class="btn btn-success">نمایش</button></a>';
        }


        $data = [
            'data' => $applications,
            'draw' => $draw,
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records
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
//        if( ! AuthAdminFacade::isLoggedIn() ) {
//            return redirect()->route('admin-login');
//         }
         
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
//        if(\App\Facades\AuthAdminFacade::getAdminTypeId() != 1){
//            abort(403);
//        }
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
