<?php

namespace App\Http\Controllers;

use App\Facades\AuthAdminFacade;
use App\Facades\AuthFacade;
use App\Facades\SabteAhvalFacade;
use App\Facades\SabteAsnadFacade;

use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\ContactInfoRepositoryInterface;
use App\Repositories\Interfaces\EducationRepositoryInterface;
use App\Repositories\Interfaces\InsuranceRepositoryInterface;
use App\Repositories\Interfaces\LegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogLegalPersonRepositoryInterface;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\PersonalInfoRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseAuthBasisRepositoryInterface;
use App\Repositories\Interfaces\PreviousLicenseRepositoryInterface;
use App\Repositories\Interfaces\RefCityRepositoryInterface;
use App\Repositories\Interfaces\RefCostRepositoryInterface;
use App\Repositories\RefProvinceRepository;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\RefLicenseTypeRepositoryInterface;
use App\Repositories\Interfaces\RefProvinceRepositoryInterface;
use App\Repositories\Interfaces\RefMrudOrganizationRepositoryInterface;
use App\Repositories\Interfaces\RefRequestTypeRepositoryInterface;
use App\Repositories\Interfaces\RefStudyFieldRepositoryInterface;
use App\Repositories\Interfaces\RefEducationGradeRepositoryInterface;
use App\Repositories\Interfaces\RefLicenseBasisRepositoryInterface;
use App\Repositories\Interfaces\RefLicenseAuthRepositoryInterface;
use App\Repositories\Interfaces\RequestAuthBasisRepositoryInterface;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use App\Rules\ValidEmploymentLicenseNumber;
use App\Rules\ValidJaliliDate;
use App\Rules\ValidMembership;
use App\Rules\ValidOrganizationNationalCode;
use App\Rules\ValidPostalCode;
use App\Rules\ValidTel;
use App\Rules\ValidTelAndCode;
use App\Rules\ValidTelCode;
use App\Services\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class Application extends Controller
{

    /**
     * Show new application page
     */
    public function create(
        RefProvinceRepositoryInterface $province,
        RefMrudOrganizationRepositoryInterface $mrud_organizations,
        RefStudyFieldRepositoryInterface $study_fields,
        RefEducationGradeRepositoryInterface $education_grades,
        RefLicenseTypeRepositoryInterface $license_types,
        RefRequestTypeRepositoryInterface $request_types,
        RefLicenseAuthRepositoryInterface $license_auth,
        RefLicenseBasisRepositoryInterface $license_basis
    )
    {
        $data = [
            'request_types' => $request_types->getAll(),
            'license_types' => $license_types->getAll(),
            'mrud_organizations' => $mrud_organizations->getAll(),
            'provinces' => $province->getAll(),
            'study_fields' => $study_fields->getAll(),
            'education_grades' => $education_grades->getAll(),
            'license_auth' => $license_auth->getAll(),
            'license_basis' => $license_basis->getAll(),
            'user' => AuthFacade::getUser(),
        ];

        return view('application.create', $data);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getCities(Request $req ,RefCityRepositoryInterface $city){

        return response()->apiRes(['cities' => $city->getProvinceCities($req->get('province_id'))]);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getStydyFields(Request $req ,RefStudyFieldRepositoryInterface $study_fields){

        return response()->apiRes(['study_fields' => $study_fields->getByGrade($req->get('grade_id'))]);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Get list of license Basis and list of license authntication based on given license type
     */
    public function getLicenseBasisAuthOptions(Request $request, RefLicenseAuthRepositoryInterface $authRep, RefLicenseBasisRepositoryInterface $basisRepo)
    {
        $data = [
            'auth_options' => $authRep->getByLicenseTypeId($request->license_type_id),
            'basis_options' => $basisRepo->getByLicenseTypeId($request->license_type_id),
        ];
        return response()->apiRes($data);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Primary Info Submit
     *
     * step in this function:
     *  - validation
     *  - inquiry legal person info from sabte ahval
     *  - inquiry organization info from sabte asnad
     *  - save info in satabse
     *  - return data
     */
    public function primaryInfoSubmit(Request $req, ApplicationRepositoryInterface $application, Auth $auth,
        RefProvinceRepositoryInterface $refProvinceRepo,
        RefStudyFieldRepositoryInterface $refStudyFieldRepo,
        RefLicenseTypeRepositoryInterface $refLicTypeRepo
    )
    {
        // Validation
        $validator = Validator::make($req->all(), [
            'persontype' => ['required', Rule::in(['regular', 'legal'])],
            // 'orgncode' => ['bail','required_if:persontype,legal', new ValidOrganizationNationalCode],
            'birthdate' => ['required', new ValidJaliliDate],
            'requesttype' => ['required', 'exists:ref_request_type,req_type_id'],
            'morg' => ['required', 'exists:ref_mrud_organizations,morg_id'],
            'studyfield' => ['required', 'exists:ref_study_field,study_field_id'],
            'licensetype' => ['required', 'exists:ref_license_type,license_type_id'],
            'province' => ['required', 'exists:ref_provinces,province_id'],
            'is_transfered' => ['bail', 'required_if:persontype,legal', 'boolean'],
            'province_src' => ['exclude_if:is_transfered,0', 'exists:ref_provinces,province_id']
        ]);
        // $validator->sometimes('orgncode', ['bail','required_if:persontype,legal', new ValidOrganizationNationalCode], function ($input) {
        //     return strlen($input->orgncode) === 11;
        // });
        // $validator->sometimes('orgncode', ['bail','required_if:persontype,legal', 'integer'], function ($input) {
        //     return strlen($input->orgncode) !== 11;
        // });
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        $special_license_types = ['205', '206'];

        // برای اشخاص حقوقی اعتبار سنجی فیلد orgncode الزامی است
        if($req->input('persontype') === 'legal'){
            // در صورتیکه فیلد licensetype برابر 205 یا 206 بود
            // آن را شماره ثبت در نظر بگیر در غیر اینصورت آن را شناسه ملی در نظر بگیر
            if(in_array($req->input('licensetype'), $special_license_types)){
                $_to_validate = [
                    'orgncode' => ['bail','required_if:persontype,legal', 'integer'],
                ];
                $org_ncode_filed_name = 'شماره ثبت';
            }else {
                $_to_validate = [
                    'orgncode' => ['bail','required_if:persontype,legal', new ValidOrganizationNationalCode],
                ];
                $org_ncode_filed_name = 'شناسه ملی';
            }

            $validator = Validator::make($req->all(), $_to_validate);
            $validator->setAttributeNames(['orgncode' => $org_ncode_filed_name]);
            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
            }

        }

        // @temp (غیرفعالسازی موقت ثبت نام برای اشخاص حقوقی)
        // if($req->input('persontype') === 'legal' ){
            // return response()->apiRes(['errors' => []], 'در حال حاضر امکان ثبت درخواست برای اشخاص حقوقی امکان پذیر نیست.', false, 400);
        // }

        $user = AuthFacade::getUser();

        // Create data to save base on person type
        $person_type = $req->input('persontype');

        $birthdate = preg_replace('/[^0-9]/', '', $req->input('birthdate'));
        // inquiry person sabte ahval
        $person_info = SabteAhvalFacade::getInfo($user->ncode, $birthdate);
        if( ! $person_info['status'] ){
            return response()->apiRes(null, 'هنگام دریافت اطلاعات از ثبت احوال مشکلی پیش آمد', false, 502);
        }

        // For legal person inquiry org info
        if($person_type === 'legal') {
            //  برای انواع مجوز حقوقی شناسه ملی استعلام گرفته میشود به جز نوع مجوز دفتر مهندسی چون شناسه ملی ندارند
            if($req->input('licensetype') == 205 || $req->input('licensetype') == 206){
                // 205 = مجوز فعالیت دفتر مهندسی طراحی ساختمان (چند نفره)
                // 206 = مجوز فعالیت دفتر مهندسی طراحی ساخت و سازهای روستایی
                // شناسه ملی ندارند. شماره ثبت دارند.
                $legal_person_info['_type'] = "office";
                $legal_person_info['Name'] = "";
                $legal_person_info['NationalCode'] = "";
                $legal_person_info['RegisterNumber'] = $req->input('orgncode');
                $legal_person_info['RegisterDate'] = $req->input('orgregdate');
                $legal_person_info['EstablishmentDate'] = "";
            }else {
                // استعلام شناسه ملی
                $legal_person_info = SabteAsnadFacade::inquirySpecialByNationalCode($req->input('orgncode'));
                if( ! $legal_person_info['status'] ){
                    return response()->apiRes(null, 'هنگام دریافت اطلاعات از ثبت اسناد مشکلی پیش آمد', false, 502);
                }
                $legal_inq = $legal_person_info['data'];
                $legal_person_info['_type'] = "organization";
                $legal_person_info['Name'] = $legal_inq['aName'];
                $legal_person_info['NationalCode'] = $legal_inq['aNationalCode'];
                $legal_person_info['RegisterNumber'] = $legal_inq['aRegisterNumber'];
                $legal_person_info['RegisterDate'] = $legal_inq['aRegisterDate'];
                $legal_person_info['EstablishmentDate'] = $legal_inq['aEstablishmentDate'];
            }
        } else {
            $legal_person_info = null;
        }

        $data = [
            'user_id' => $auth->getUserId(),
            'applicant_type_id' => ($person_type === 'regular')? 1:  2,
            'user_person_ncode' => $user->ncode,
            'user_org_ncode' => ($person_type === 'regular')? NULL:  $req->input('orgncode'),
            'user_org_reg_date' => ($person_type === 'legal' && in_array($req->input('licensetype'), $special_license_types) )?  $req->input('orgregdate'): NULL,
            'user_mobile' => $user->mobile,
            'user_birthdate' => $req->input('birthdate'),
            'app_type_id' => $req->input('requesttype'),
            'license_type_id' => $req->input('licensetype'),
            'mrud_org_id' => $req->input('morg'),
            'edu_field_id' => $req->input('studyfield'),
            'province_id' => $req->input('province'),
            'is_transfered' => intval($req->input('is_transfered')),
            'source_province_id' => ($req->input('is_transfered')) ? $req->input('province_src') : 0,
        ];

        $saved_id = $application->create($data);
        if( ! $saved_id ){
            $res = [
                'data' => null,
                'message'=> 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کنید' . $saved_id
            ];
            return response()->json($res);
        }

        $req->session()->forget('application_id');
        $req->session()->put('application_id', $saved_id); // Keep application id to use in next steps

        $contactInfo = [
            'data' => ['mobile' => $user->mobile]
        ];

        $membership_id = $this->_calculateMembershipNumber($person_type, $req->input('province'),$req->input('studyfield'), $refProvinceRepo, $refStudyFieldRepo);
        $employment_lic_number = $this->_calculateEmploymentLicenseNumber($person_type,$req->input('licensetype'), $req->input('province'),$req->input('studyfield'), $refLicTypeRepo, $refProvinceRepo, $refStudyFieldRepo);

        // در صورتیکه عضو انتقالی باشد شماره پروانه بر اساس استان مبدا محاسه میشود
        // بنابراین باید از مقدار ارسال شده در پارامتر province_src استفاده کرد
        if($req->input('is_transfered')) {
            $employment_lic_number = $this->_calculateEmploymentLicenseNumber($person_type,$req->input('licensetype'), $req->input('province_src'),$req->input('studyfield'), $refLicTypeRepo, $refProvinceRepo, $refStudyFieldRepo);
        }

        $res = [
            'data' => [
                'personinfo' => $person_info['data'],
                'legalpersoninfo' => $legal_person_info,
                'contactinfo' => $contactInfo['data'],
                'membershipid' => $membership_id,
                'employment_license_number' => $employment_lic_number
            ],
            'message'=> 'اطلاعات ثبت شد'
        ];
        return response()->json($res);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Legal Person Submit
     */
    public function legalPersonSubmit(Request $req, LegalPersonRepositoryInterface $lega_person, LogLegalPersonRepositoryInterface $logLegalPerson)
    {
        // application is added in middleware
        $application = $req->input('application');

        // Get organization info from log_legal_person
        $organiztion = $logLegalPerson->find($application->user_org_ncode);
        if( ! $organiztion ) {
            return response()->apiRes(null, 'استعلام شرکت شما موجود نمی باشد', false, 404);
        }

        // create data to save base on person type
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'org_name' => $organiztion->Name,
            'org_ncode' => $organiztion->NationalCode,
            'org_reg_number' => $organiztion->RegisterNumber,
            'org_reg_date' => $organiztion->RegisterDate,
            'org_estblishment_date' => $organiztion->EstablishmentDate
        ];

        // create new record or if exists update record
        $save = $lega_person->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کن', false, 503);
        }

        return response()->apiRes(null, 'اطلاعات حقوقی ذخیره شدند');
    }

    // ---------------------------------------------------------------------------------------------------

    public function personalInfoSubmit(Request $req, PersonalInfoRepositoryInterface $personRep, LogSabteAhvalRepositoryInterface $sabteAhvalLog)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'marital_status' => ['required', Rule::in([0, 1])],
            'birthplace' => ['required', 'exists:ref_provinces,province_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        // Get person info from log_sabte_ahval
        $person = $sabteAhvalLog->find($application->user_person_ncode);
        if( ! $person ) {
            return response()->apiRes(null, 'استعلام شخص شما موجود نمی باشد', false, 404);
        }

        // create data to save base on person type
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'p_name' => $person->name,
            'p_lname' => $person->lname,
            'p_ncode' => $person->ncode,
            'p_birth_date' => $person->birthdate,
            'p_certificate_id' => $person->shenasnameh_no,
            'p_gender_id' => $person->gender,
            'p_fname' => $person->fname,
            'p_birth_location' => $req->post('birthplace'),
            'p_marriage_status_id' => $req->post('marital_status'),
        ];

        // create new record or if exists update record
        $save = $personRep->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کن', false, 503);
        }

        return response()->apiRes(null, 'اطلاعات هویتی ذخیره شدند');
    }

    // ---------------------------------------------------------------------------------------------------

    public function contactInfoSubmit(Request $req, ContactInfoRepositoryInterface $contactRep,RefProvinceRepositoryInterface $provinceRep)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'email' => ['nullable','email'],
            'tel' => ['required', new ValidTel],
            'postalcode' => ['required', new ValidPostalCode],
            'province' => ['required', 'exists:ref_provinces,province_id'],
            'city' => ['required', 'exists:ref_cities,city_id'],
            'street1' => ['required'],
            'work_address' => ['exclude_if:person_type,legal'],
            'work_tel_number' => ['exclude_if:person_type,legal', new ValidTelAndCode],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        // create data to save base on person type
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'mobile' => $application->user_mobile,
            'email' => $req->input('email'),
            'tel_zone' => $provinceRep->find($req->input('province'))->code,
            'tel_number' => $req->input('tel'),
            'postal_code' => $req->input('postalcode'),
            'province_id' => $req->input('province'),
            'city_id' => $req->input('city'),
            'street1' => $req->input('street1'),
            'street2' => $req->input('street2'),
            'no' => $req->input('no'),
            'floor' => $req->input('floor'),
            'unit' => $req->input('unit'),
            'work_address' => $req->input('work_address'),
            'work_tel_number' => $req->input('work_tel_number'),
        ];

        // create new record or if exists update record
        $save = $contactRep->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کنید', false, 503);
        }

        return response()->apiRes(null, 'اطلاعات تماس ذخیره شد');
    }

    // ---------------------------------------------------------------------------------------------------

    public function insuranceInfosubmit(Request $req, InsuranceRepositoryInterface $insRep)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'haveins' => ['required', Rule::in([0, 1])],
            'instype' => ['bail','required_if:haveins,1'],
            'insplace' => ['bail','required_if:haveins,1'],
            'insoccupation' => ['bail','required_if:haveins,1'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        // create data to save base on person type
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'mobile' => $application->user_mobile,
            'ins_available' => $req->input('haveins'),
            'ins_type' => $req->input('instype'),
            'ins_pay_location' => $req->input('insplace'),
            'ins_main_occupation' => $req->input('insoccupation')
        ];

        // create new record or if exists update record
        $save = $insRep->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کنید', false, 503);
        }

        return response()->apiRes(null, 'اطلاعات بیمه ذخیره شد');
    }

    // ---------------------------------------------------------------------------------------------------

    public function educationInfoSubmit(Request $req, EducationRepositoryInterface $eduRep)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'educationRecords' => "required|array|min:1",
            'educationRecords.*.grade' => ['bail','required','exists:ref_education_grade,education_grade_id'],
            'educationRecords.*.field' => ['bail','required','exists:ref_study_field,study_field_id'],
            'educationRecords.*.area' => ['bail','required'],

        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        $eduRep->deleteAll($application->application_id);

        $records = $req->input('educationRecords');

        $saved_records = 0;

        foreach($records as $record){
            $data = [
                'user_id' => $application->user_id,
                'application_id' => $application->application_id,
                'edu_grade_id' => $record['grade'],
                'edu_field_id' => $record['field'],
                'edu_area' => $record['area'],
            ];

            $save = $eduRep->create($application->application_id, $data);
            if( $save ){
                $saved_records++;
            }
        }

        if(count($records) != $saved_records){
            // create new record or if exists update record
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کنید' . '|' . count($records) . '|' . $saved_records , false, 503);
        }

        return response()->apiRes(null, 'اطلاعات تحصیلی ذخیره شد');
    }

    // ---------------------------------------------------------------------------------------------------

    public function requestInfoSubmit(Request $req, RequestAuthBasisRepositoryInterface $reqAuthRep, RequestRepositoryInterface $reqRep)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'membershipid' => ['bail', 'required', new ValidMembership],
            'anjomanmembershipid' => ['max:20'],
            'requestRecords' => "required|array|min:1",
            'requestRecords.*.auth' => ['bail','required','exists:ref_license_authentication,lic_auth_id'],
            'requestRecords.*.basis' => ['bail','required','exists:ref_license_basis,lic_basis_id'],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        // Attempt to save request fileds
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'req_mrud_org_id' => $application->mrud_org_id,
            'req_province_id' => $application->province_id,
            'req_membership_no' => $req->input('membershipid'),
            'req_anjoman_membership_no' => $req->input('anjomanmembershipid'),
            'req_license_type_id' => $application->license_type_id,
            'req_type_id' => $application->app_type_id
        ];

        $save = $reqRep->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام ذخیره اطلاعات پیش آمد. دوباره تلاش کنید', false, 503);
        }

        // Delete previous records and Attempt to save records
        $reqAuthRep->deleteAll($application->application_id);

        $records = $req->input('requestRecords');
        $saved_records = 0;
        foreach($records as $record){
            $data = [
                'user_id' => $application->user_id,
                'application_id' => $application->application_id,
                'request_id' => $save,
                'license_auth_id' => $record['auth'],
                'license_basis_id' => $record['basis'],
            ];

            $save = $reqAuthRep->create($application->application_id, $data);
            if( $save ){
                $saved_records++;
            }
        }

        if(count($records) != $saved_records){
            // create new record or if exists update record
            return response()->apiRes(null, 'مشکلی هنگام خیره اطلاعات پیش آمد. دوباره تلاش کنید' . '|' . count($records) . '|' . $saved_records , false, 503);
        }

        return response()->apiRes(null, 'اطلاعات درخواست ذخیره شد');
    }


    // ---------------------------------------------------------------------------------------------------

    public function previousLicenseInfoSubmit(Request $req, PreviousLicenseAuthBasisRepositoryInterface $plicAuthRep, PreviousLicenseRepositoryInterface $plicRep)
    {
        
        // Validation
        $validator = Validator::make($req->all(), [
            'licenseno' => ['bail', 'required', new ValidEmploymentLicenseNumber],
            'licenseserialno' => ['bail', 'required', 'numeric'],
            'firstlicensedate' => ['bail', 'required', new ValidJaliliDate],
            'expirationdate' => ['bail', 'required', new ValidJaliliDate],
            'records' => "required|array|min:1",
            'records.*.auth' => ['bail','required','exists:ref_license_authentication,lic_auth_id'],
            'records.*.basis' => ['bail','required','exists:ref_license_basis,lic_basis_id'],
            'records.*.date' => ['bail','required', new ValidJaliliDate],
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }


        // application is added in middleware
        $application = $req->input('application');

        // Attempt to save pevious license fields
        $data = [
            'user_id' => $application->user_id,
            'application_id' => $application->application_id,
            'plic_no' => $req->input('licenseno'),
            'plic_serial_no' => $req->input('licenseserialno'),
            'plic_date_first_issue' => $req->input('firstlicensedate'),
            'plic_date_expire' => $req->input('expirationdate')
        ];

        $save = $plicRep->createOrUpdate($application->application_id, $data);
        if( ! $save ){
            return response()->apiRes(null, 'مشکلی هنگام ذخیره اطلاعات پیش آمد. دوباره تلاش کنید', false, 503);
        }

        // Delete previous records and Attempt to save records
        $plicAuthRep->deleteAll($application->application_id);

        $records = $req->input('records');
        $saved_records = 0;
        foreach($records as $record){
            $data = [
                'user_id' => $application->user_id,
                'application_id' => $application->application_id,
                'prev_license_id' => $save,
                'plic_auth' => $record['auth'],
                'plic_basis' => $record['basis'],
                'plic_auth_date' => $record['date'],
            ];

            $save = $plicAuthRep->create($application->application_id, $data);
            if( $save ){
                $saved_records++;
            }
        }

        if(count($records) != $saved_records){
            // create new record or if exists update record
            return response()->apiRes(null, 'مشکلی هنگام ذخیره اطلاعات پیش آمد. دوباره تلاش کنید', false, 503);
        }

        return response()->apiRes(null, 'اطلاعات پروانه قبلی ذخیره شد');
    }

    // ---------------------------------------------------------------------------------------------------



    public function previousLicenseImageUpload(Request $req)
    {
        // application is added in middleware
        $application = $req->input('application');

        // validation
        $validator = Validator::make($req->all(), [
            'file' => 'required|mimes:png,jpeg|max:500'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->apiRes(['errors' => $errors], trans('validation.error'), false, 400);
        }

        $file_path = 'application/' . $application->application_id;
        if($req->file()) {
            $file_name = $req->file->getClientOriginalName() . '.' . $req->file->extension();
            $filePath = $req->file('file')->storeAs($file_path , $file_name);
            return response()->json(['filePath' => $filePath, 'ext' => $req->file->extension()]);
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getPreview(Request $req,
        LegalPersonRepositoryInterface $legalPersonRep,
        PersonalInfoRepositoryInterface $personalInfoRep,
        ContactInfoRepositoryInterface $contactRep,
        EducationRepositoryInterface $educationRep,
        RequestRepositoryInterface $requestRep,
        RequestAuthBasisRepositoryInterface $reqAuthRep,
        InsuranceRepositoryInterface $insRep,
        PreviousLicenseRepositoryInterface $previousLiceseRep,
        PreviousLicenseAuthBasisRepositoryInterface $previousLicenseAuthRep)
    {
        // application is added in middleware
        $app = $req->input('application');

        $previous_license_exists = ($app->app_type_id == 4) ? false: true;

        $legal_person = $legalPersonRep->getByAppId($app->application_id);
        $personal_info = $personalInfoRep->getByAppId($app->application_id);
        $contact_info = $contactRep->getByAppId($app->application_id);
        $education_records = $educationRep->getByAppId($app->application_id);
        $request =$requestRep->getByAppId($app->application_id);
        $request_records = $reqAuthRep->getByAppId($app->application_id);
        $insurance_info = $insRep->getByAppId($app->application_id);
        $previous_license = $previousLiceseRep->getByAppId($app->application_id);
        $previous_license_records = $previousLicenseAuthRep->getByAppId($app->application_id);
        $previous_license_images = $previousLiceseRep->getImages($app->application_id);

        $data = [
            'legalperson' => $legal_person,
            'personal_info' => $personal_info,
            'contact_info' => $contact_info,
            'education_records' => $education_records,
            'request' => $request,
            'request_records' => $request_records,
            'insurance_info' => $insurance_info,
            'previous_license_exists' => $previous_license_exists,
            'previous_license' => $previous_license,
            'previous_license_records' => $previous_license_records,
            'previous_license_images' => $previous_license_images,
        ];

        return response()->apiRes($data, 'اطلاعات پیش نمایش دریافت شد');
    }

    // ---------------------------------------------------------------------------------------------------

    public function previewSubmit(Request $req, ApplicationRepositoryInterface $appRep)
    {
        // application is added in middleware
        $application = $req->input('application');

        $update = $appRep->update($application->application_id, ['status_id' => 2]);

        if($update){
            return response()->apiRes(['appid' => $application->application_id], 'درخواست شما با موفقیت ثبت شد. اکنون نسبت به پرداخت هزینه اقدام بفرمایید');
        } else {
            return response()->apiRes(null, 'خطایی هنگام تایید نهایی رخ داد. مجددا تلاش فرمایید.', false, 503);
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getFile(Request $req)
    {
        $info = explode('-', $req->get('file'));
        $app_id = $info[0];
        $file_name = $info[1];

        $file_path = "application/{$app_id}/{$file_name}";

        // if ( ! Storage::exists($file_path) ) {
        //     $file_path = null;
        // }

        $absolute_disk_path  = Storage::disk()->getAdapter()->getPathPrefix();
        $mime = File::mimeType($absolute_disk_path  . $file_path);

        $contents = Storage::get($file_path);
        return response($contents)
            ->header('Content-Type', $mime);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getTrackingCode(Request $request, ApplicationRepositoryInterface $appRepo, PaymentRepositoryInterface $payRepo)
    {
        // Check if application exists and owned by requester
        $application = $appRepo->find($request->application_id);
        if( ! $application || ($application->user_id != AuthFacade::getUserId()) ) {
            return response()->apiRes(null, 'درخواست شما قابل پردازش نیست');
        }

        // Get payment
        $payment = $payRepo->findGtpPayedRecordByApplication($application->application_id);
        if($payment) {
            $serial = $payment->order_number;
        } else {
            $serial = null;
        }

        $data = [
            'application_id' => $application->application_id,
            'tracking_code' => $application->tracking_code ,
            'serial' => $serial
        ];
        return view('application.steps.step-trackingcode', $data);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getReceipt(Request $request, ApplicationRepositoryInterface $appRepo, PaymentRepositoryInterface $payRepo)
    {
        // Check if application exists and owned by requester
        $application = $appRepo->getApplication($request->application_id);
        if( ! $application ) {
            return response()->apiRes(null, 'درخواست شما قابل پردازش نیست');
        }

        // نمایش رسید برای متقاضی یا ادمین امکان پذیر است
        if( ($application->user_id != AuthFacade::getUserId()) && AuthAdminFacade::getAdminId() === false ) {
            return response()->apiRes(null, 'دسترسی به رسید امکان پذیر نیست');
        }


        $applicant_name = $application->p_name . ' ' . $application->p_lname;
        $applicant_name .= ($application->org_name) ? ' / ' . $application->org_name : '';
        $applicant_ncode = $application->p_ncode . ' / ' . $application->org_ncode;

        // Get payment
        $payment = $payRepo->findGtpPayedRecordByApplication($application->application_id);


        $data = [
            'tracking_code' => $application->tracking_code, // کد پیگیری
            'request_type' => $application->req_type_title, // نوع درخواست
            'province' => $application->province_title, // استان
            'license_type' => $application->license_type_title, // نوع پروانه
            'organization' => $application->morg_title, // سازمان
            'study_field' => $application->study_field_title, // عنوان مدرک تحصیلی
            'applicant_ncode' => $applicant_ncode, // کد ملی / شناسه ملی
            'applicant_name' => $applicant_name, // نام و نام خانوادگی / نام شرکت
            'pay_date' => $payment->updated_at, // تاریخ پرداخت
            'pay_amount' => $payment->amount, // مبلغ پرداخت
            'pay_number' => $payment->order_number, // سریال تراکنش
        ];

        return view('application.receipt', $data);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     *
     * Jalalian & CalendarUtils classes reference: https://github.com/morilog/jalali
     */
    public function list(ApplicationRepositoryInterface $appRep, Auth $auth)
    {
        $applications = $appRep->getUserApplications($auth->getUserId());
        for($i=0; $i < count($applications); $i++){
            // Convert Georgian to Jalali date
            $created_at_jalali = Jalalian::forge(strtotime($applications[$i]->created_at))->format("Y-m-d");
            // Convert numbers to persian characters
            $applications[$i]->created_at= CalendarUtils::convertNumbers($created_at_jalali);
        }
        $data['applications'] = $applications;
        return view('application.list', $data);
    }

    public function successApplications(ApplicationRepositoryInterface $appRep, Auth $auth)
    {
        $applications = $appRep->getUserSuccessApplications($auth->getUserId());
        for($i=0; $i < count($applications); $i++){
            // Convert Georgian to Jalali date
            $created_at_jalali = Jalalian::forge(strtotime($applications[$i]->created_at))->format("Y-m-d");
            // Convert numbers to persian characters
            $applications[$i]->created_at= CalendarUtils::convertNumbers($created_at_jalali);
        }
        return response()->json($applications);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getLatestApplicationData(
        ApplicationRepositoryInterface $appRepo,
        PersonalInfoRepositoryInterface $persRep,
        ContactInfoRepositoryInterface $conRep,
        EducationRepositoryInterface $eduRep,
        RequestAuthBasisRepositoryInterface $reqAuthRep,
        InsuranceRepositoryInterface $insRep,
        RequestRepositoryInterface $reqRep,
        PreviousLicenseRepositoryInterface $preLicRep,
        PreviousLicenseAuthBasisRepositoryInterface $preLicAuthRep
    )
    {
        $user_id = AuthFacade::getUserId();
        $user = AuthFacade::getUser();
        $application = [];
        $application['app_type_id'] = null;
        $application['applicant_type_id'] = $user->person_type_id;
        $application['edu_field_id'] = null;
        $application['license_type_id'] = null;
        $application['province_id'] = null;
        $application['user_birthdate'] = $user->birthdate;
        $application['user_mobile'] = $user->mobile;
        $application['user_org_ncode'] = $user->org_code;
        $application['user_person_ncode'] = $user->ncode;

        $person = $persRep->getUserLastestRawData($user_id);
        $contact = $conRep->getUserLastestRawData($user_id);
        $insurance = $insRep->getUserLastestRawData($user_id);
        $education_records = $eduRep->getUserLastestRawData($user_id);
        $request = $reqRep->getUserLastestRawData($user_id);
        $request_records = $reqAuthRep->getUserLastestRawData($user_id);
        $previous_license = $preLicRep->getUserLastestRawData($user_id);
        $previous_license_records = $preLicAuthRep->getUserLastestRawData($user_id);

        $data = [
            'application' => $application,
            'person' => $person,
            'contact' => $contact,
            'insurance' => $insurance,
            'education_records' => $education_records,
            'request' => $request,
            'request_records' => $request_records,
            'previous_license' => $previous_license,
            'previous_license_records' => $previous_license_records,
        ];

        return response()->apiRes($data);
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Calculates employment license number based on following options: (محاسبه شماره پروانه اشتغال بکار)
     *   1 - person type
     *   2 - license type code
     *   3 - province code
     *   4 - study field code (فقط برای اشخاص حقیقی)
     *
     * Pattern for regular person (الگو پروانه اشخاص حقیقی)
     * license code (2 chars) - province code (2 chars) - study field code (with zero in end) - uniqueid (1 to 6 chars)
     *
     * Pattern for legal person (الگو پروانه اشخاص حقوقی)
     * license code (2 chars) - province code (2 chars) - uniqueid (1 to 6 chars)
     *
     * Pattern for legal person (الگو پروانه اشخاص حقوقی در حالت استثا)
     * license code (2 chars) - province code (2 chars) - 01 - uniqueid (1 to 6 chars)
     *
     * pattern for license code 109 (الگو پروانه برای نوع پروانه کارشناسی رسمی ماده 27 قانون نظام مهندسی و کنترل ساختمان.)
     * ک - province code (2 chars) - study field code (2 chars) - uniqueid (1 to 6 chars)
     *
     * pattern for license code 205 (مجوز فعالیت دفتر مهندسی طراحی ساختمان (چند نفره) )
     * license code (2 chars) - province code (2 chars) - study field code (from 1 to 7) - uniqueid (1 to 6 chars)
     *
     * More info: Refer to Task #9170 in op.
     */
    protected function _calculateEmploymentLicenseNumber(
        $person_type,
        $license_type_id,
        $province_id,
        $study_field_id,
        RefLicenseTypeRepositoryInterface $refLicTypeRepo,
        RefProvinceRepositoryInterface $refProvinceRepo,
        RefStudyFieldRepositoryInterface $refStudyFieldRepo
    )
    {
        // Get license type code based on posted license type ID.
        $license_type_code = $refLicTypeRepo->find($license_type_id)->license_type_code;

        // Get province code based on posted province ID.
        $province_code = $refProvinceRepo->find($province_id)->lic_province_code;

        if($person_type === 'regular') {

            // برای رشته معمار تجربی و سایر شماره پروانه کدرشته ندارد
            if($study_field_id == 87 || $study_field_id == 88) {
                $license_number = "{$license_type_code}-{$province_code}-XXXXX";

            } else {
                // Get education code based on posted education ID.
                $education_code = $refStudyFieldRepo->find($study_field_id)->study_field_code;

                if($license_type_id == '109') {
                    // اگر کارشناسی رسمی ماده 27 قانون نظام مهندسی و کنترل ساختمان باشد الگوریتم محاسبه شماره پروانه به شکل زیر است.
                    $license_number = "ک-{$province_code}-{$education_code}-XXXXXX";
                } else {
                    // Calculate license number for regular person
                    $license_number = "{$license_type_code}-{$province_code}-{$education_code}-XXXXX";
                }

            }

        } else {

            if($license_type_id == '205') {
                // الگوریتم مجوز فعالیت دفتر مهندسی طراحی ساختمان (چند نفره)
                $education_code = $refStudyFieldRepo->find($study_field_id)->study_field_code;
                $num = (int) round($education_code / 10, 0, PHP_ROUND_HALF_DOWN );
                $license_number = "{$license_type_code}-{$province_code}-{$num}-XXXXXX";
            } else {
                // Calculate license number for legal person
                $license_number = "{$license_type_code}-{$province_code}-XXXXX";
            }

            // calculate license number for legal person exceptional (حالت استثنا محاسبه شماره پروانه برای افراد حقوقی)
           // $is_exception = $this->_check_exception($license_type_code, $license_basis_id);
            // if($is_exception) {
            //     $license_number = "{$license_type_code}-{$province_code}-01-XXXXX";
            // }

        }

        return $license_number;
    }

    // ---------------------------------------------------------------------------------------------------

    protected function _check_exception($license_type_code, $license_basis_id) {

        $is_exception =  false;
        switch (true){
            case ($license_type_code == 201 && $license_basis_id == 26): // پایه 1
                $is_exception = true;
                break;
            case ($license_type_code == 207 && $license_basis_id == 45): // پایه 1
                $is_exception = true;
                break;
            case ($license_type_code == 209 && $license_basis_id == 51): // پایه 1
                $is_exception = true;
                break;
            case ($license_type_code == 210 && $license_basis_id == 54): // پایه 1
                $is_exception = true;
                break;
            case ($license_type_code == 203 && $license_basis_id == 32): // پایه ارشد
                $is_exception = true;
                break;
            case ($license_type_code == 204 && $license_basis_id == 0): // پایه ارشد
                $is_exception = true;
                break;
        }

        return $is_exception;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Calculates membership number based on following options: (محاسبه شماره عضویت)
     *   1 - person type
     *   2 - province code
     *   3 - study field code (فقط برای اشخاص حقیقی)
     *
     * Pattern for regular person (الگو شماره عضویت اشخاص حقیقی)
     * province code (2 chars) - ح - uniqueid (5 or 6 chars)
     *
     * Pattern for legal person (الگو شماره عضویت اشخاص حقوقی)
     * province code (2 chars) - study field code (1st num) - study field code (2nd num)  - uniqueid (5 or 6 chars)
     *
     * More info: Refer to Task #9171 in op.
     */

    protected function _calculateMembershipNumber(
        $person_type,
        $province_id,
        $study_field_id,
        RefProvinceRepositoryInterface $refProvinceRepo,
        RefStudyFieldRepositoryInterface $refStudyFieldRepo)
    {
        // برای رشته معمار تجربی و سایر الگوریتمی برای محاسبه شماره عضویت وجود ندارد
        if($study_field_id == 87 || $study_field_id == 88) {
            return "";
        }

        // Get province code based on posted province ID.
        $province_code = $refProvinceRepo->find($province_id)->lic_province_code;

        if($person_type === 'regular') {
            // Get education code based on posted education ID.
            $education_code = (int) $refStudyFieldRepo->find($study_field_id)->study_field_code;
            // $num1 = $education_code / 10;
            $num1 = (int) round($education_code / 10, 0, PHP_ROUND_HALF_DOWN );
            $num2 = $education_code % 10;

            // Calculate license number for regular person
            $membership_number = "{$province_code}-{$num1}-{$num2}-XXXXX";

        } else {
            // Calculate license number for legal person
            $membership_number = "{$province_code}-ح-XXXXX";
        }

        return $membership_number;
    }

    // ---------------------------------------------------------------------------------------------------

    public function priceList(RefCostRepositoryInterface $costRepo){
        $data = [
            'prices' => $costRepo->getPriceList(),
        ];

        return view('application.price-list', $data);
    }

    public function cost(Request $request)
    {
        $cost = DB::table('ref_costs')->where('person_type_id',$request->p_id)->where('request_type_id',$request->type_id)->first();
        return response()->json(['cost' => $cost->amount]);
    }

}
