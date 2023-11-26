<?php

namespace App\Repositories;

use App\Models\ApplicationModel;
use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Services\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationRepository implements ApplicationRepositoryInterface{

    public function find($id){
        return DB::table('tbl_application')->where('application_id', $id)->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getAll(){
        // return User::all();
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($data){
        $application = new ApplicationModel;
        $application->user_id = $data['user_id'];
        $application->applicant_type_id = $data['applicant_type_id'];
        $application->user_org_ncode = $data['user_org_ncode'];
        $application->user_person_ncode = $data['user_person_ncode'];
        $application->user_org_reg_date = $data['user_org_reg_date'];
        $application->user_mobile = $data['user_mobile'];
        $application->user_birthdate = $data['user_birthdate'];
        $application->app_type_id = $data['app_type_id'];
        $application->license_type_id = $data['license_type_id'];
        $application->mrud_org_id = $data['mrud_org_id'];
        $application->province_id = $data['province_id'];
        $application->is_transfered = $data['is_transfered'];
        $application->source_province_id = $data['source_province_id'];
        $application->edu_field_id = $data['edu_field_id'];
        $application->status_id = 1; // 1 means new, refer to ref_app_status table
        if($application->save()){
            // Get application_id
            $app_id = $application->application_id;
            // Calculate trackingcode based on application_id then update  record
            $tracking_code = calculate_trackingcode($app_id, $application->created_at);
            $application->update(['tracking_code' => $tracking_code]);
            return $app_id;

        } else {
            return false;
        } 
    }

    // ---------------------------------------------------------------------------------------------------

    public function update($id, $data){
        return ApplicationModel::find($id)->update($data);
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserApplications($user_id)
    {
        return DB::table('tbl_application AS app')
            ->select('app.application_id' ,'app.created_at', 'req.req_type_title', 'lic.license_type_title', 'org.morg_title', 'pro.province_title', 'edu.study_field_title', 'app.status_id', 'stat.status_title')
            ->leftJoin('ref_request_type AS req', 'app.app_type_id', '=', 'req.req_type_id')
            ->leftJoin('ref_license_type AS lic', 'app.license_type_id', '=', 'lic.license_type_id')
            ->leftJoin('ref_mrud_organizations AS org', 'app.mrud_org_id', '=', 'org.morg_id')
            ->leftJoin('ref_provinces AS pro', 'app.province_id', '=', 'pro.province_id')
            ->leftJoin('ref_study_field AS edu', 'app.edu_field_id', '=', 'edu.study_field_id')
            ->leftJoin('ref_app_status AS stat', 'app.status_id', '=', 'stat.status_id')
            ->where('app.status_id', '>' , 1)
            ->where('app.user_id', $user_id)
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getApplicationFullDetails($application_id)
    {
        return DB::table('tbl_application AS app')
            ->select(
                'app.created_at', 'app.user_id', 'app.application_id', 'app.tracking_code', 'app.applicant_type_id',
                'req.req_type_title', 'lic.license_type_title', 'org.morg_title', 
                'pro.province_title', 'edu.study_field_title', 'stat.status_title', 
                'pers.p_name', 'p_lname', 'pers.p_ncode', 'pers.p_birth_date', 'p_fname', 'pro2.province_title as pers_birth_location', 'p_marriage_status_id',
                'organ.org_name', 'organ.org_ncode',
                'con.mobile', 'con.email', 'con.tel_zone', 'con.tel_number', 'con.postal_code', 'con.street1', 'con.street2', 'con.no', 'con.floor', 'con.unit', 'con.work_address', 'con.work_tel_number',
                'ins_available', 'ins_type',  'ins_main_occupation',
                'tblreq.req_membership_no', 'tblreq.req_anjoman_membership_no',
                )
            ->leftJoin('ref_request_type AS req', 'app.app_type_id', '=', 'req.req_type_id')
            ->leftJoin('ref_license_type AS lic', 'app.license_type_id', '=', 'lic.license_type_id')
            ->leftJoin('ref_mrud_organizations AS org', 'app.mrud_org_id', '=', 'org.morg_id')
            ->leftJoin('ref_provinces AS pro', 'app.province_id', '=', 'pro.province_id')
            ->leftJoin('ref_study_field AS edu', 'app.edu_field_id', '=', 'edu.study_field_id')
            ->leftJoin('ref_app_status AS stat', 'app.status_id', '=', 'stat.status_id')
            ->leftJoin('tbl_person_info AS pers', 'app.application_id', '=', 'pers.application_id')
            ->leftJoin('tbl_org_info AS organ', 'app.application_id', '=', 'organ.application_id')
            ->leftJoin('ref_provinces AS pro2', 'pers.p_birth_location', '=', 'pro2.province_id')
            ->leftJoin('tbl_contact_info AS con', 'app.application_id', '=', 'con.application_id')
            ->leftJoin('tbl_insurance_info AS ins', 'app.application_id', '=', 'ins.application_id')
            ->leftJoin('tbl_request_info AS tblreq', 'app.application_id', '=', 'tblreq.application_id')

            ->where('app.application_id', $application_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getApplication($application_id)
    {
        return DB::table('tbl_application AS app')
            ->select('app.created_at', 'app.user_id', 'app.application_id', 'app.tracking_code', 'req.req_type_title', 'lic.license_type_title', 'org.morg_title',
                'pro.province_title', 'edu.study_field_title', 'stat.status_title', 'pers.p_name', 'p_lname', 'pers.p_ncode', 'organ.org_name', 'organ.org_ncode')
            ->leftJoin('ref_request_type AS req', 'app.app_type_id', '=', 'req.req_type_id')
            ->leftJoin('ref_license_type AS lic', 'app.license_type_id', '=', 'lic.license_type_id')
            ->leftJoin('ref_mrud_organizations AS org', 'app.mrud_org_id', '=', 'org.morg_id')
            ->leftJoin('ref_provinces AS pro', 'app.province_id', '=', 'pro.province_id')
            ->leftJoin('ref_study_field AS edu', 'app.edu_field_id', '=', 'edu.study_field_id')
            ->leftJoin('ref_app_status AS stat', 'app.status_id', '=', 'stat.status_id')
            ->leftJoin('tbl_person_info AS pers', 'app.application_id', '=', 'pers.application_id')
            ->leftJoin('tbl_org_info AS organ', 'app.application_id', '=', 'organ.application_id')
            ->where('app.status_id', 3)
            ->where('app.application_id', $application_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_application AS app')
            ->select("applicant_type_id","user_org_ncode","user_person_ncode","user_mobile","user_birthdate","app_type_id","license_type_id","mrud_org_id","province_id", "edu_field_id")
            ->where('app.user_id', $user_id)
            ->orderBy("application_id", 'DESC')
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getApplicationByTrackingCode($tracking_code)
    {
        return DB::table('tbl_application AS app')
            ->select('app.tracking_code', 'app.applicant_type_id AS person_type_id', 'pers.p_ncode', 'organ.org_name', 'organ.org_ncode','org.morg_title', DB::raw('CONCAT (pers.p_name, " ", pers.p_lname) AS fullname '))
            ->leftJoin('tbl_person_info AS pers', 'app.application_id', '=', 'pers.application_id')
            ->leftJoin('tbl_org_info AS organ', 'app.application_id', '=', 'organ.application_id')
            ->leftJoin('ref_mrud_organizations AS org', 'app.mrud_org_id', '=', 'org.morg_id')
            ->where('app.status_id', 3)
            ->where('app.tracking_code', $tracking_code)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * این متد درخواستهایی که توسط کاربران ثبت نهایی شده اند یعنی مقدار وضعیت آنها جز یک هستند را بر میگرداند
     */
    public function getApplications($length = 10, $start = 0, $conditions = null)
    {
        $db = DB::table('tbl_application AS app')
            //->select('app.application_id' ,'app.created_at', 'req.req_type_title', 'pro.province_title', 'app.status_id', 'stat.status_title', 'pers.p_name', 'pers.p_lname', 'pers.p_ncode', 'pers.p_id AS pid','app.user_mobile', 'app.tracking_code', 'pt.person_type_title')
            ->select(DB::raw('app.application_id ,app.created_at, req.req_type_title, pro.province_title, app.status_id, stat.status_title, pers.p_name, pers.p_lname, pers.p_ncode,app.user_mobile,app.tracking_code,pt.person_type_title'))
            ->leftJoin('tbl_person_info AS pers', 'app.application_id', '=', 'pers.application_id')
            ->leftJoin('ref_request_type AS req', 'app.app_type_id', '=', 'req.req_type_id')
            ->leftJoin('ref_provinces AS pro', 'app.province_id', '=', 'pro.province_id')
            ->leftJoin('ref_app_status AS stat', 'app.status_id', '=', 'stat.status_id')
            ->leftJoin('ref_person_type AS pt', 'app.applicant_type_id', '=', 'pt.person_type_id')
            ->where('app.status_id', '>' , 1)
            ->groupBy('pers.application_id')
            ->groupBy('pers.p_name')
            ->groupBy('pers.p_lname')
            ->groupBy('pers.p_ncode')
            ->groupBy('app.application_id');

            if(is_array($conditions)){
                if(isset($conditions['p_ncode']) && ! is_null($conditions['p_ncode'])){
                    $db->where('app.user_person_ncode', $conditions['p_ncode']);
                }
                if(isset($conditions['province_id']) && ! is_null($conditions['province_id']) ){
                    $db->where('app.province_id', $conditions['province_id']);
                }
                if(isset($conditions['mobile']) && ! is_null($conditions['mobile']) ){
                    $db->where('app.user_mobile', $conditions['mobile']);
                }
                
            }

            $count = $db->count();
            $records = $db->orderBy('app.application_id', 'DESC')->skip($start)->take($length)->get();
            return [$count, $records];
    }

    // ---------------------------------------------------------------------------------------------------

    public function countPayedApplicationsBasedOnRequestType($request_type) {
        return DB::table('tbl_application')
                    ->where('status_id', 3) //payed
                    ->where('app_type_id', $request_type)
                    ->count();
    }

    // ---------------------------------------------------------------------------------------------------

    public function countPayedApplicationsBasedOnProvince($person_type) {
        return DB::select(DB::raw("select app.province_id, count(distinct app.application_id) as count, ref.province_title from tbl_application app left join ref_provinces ref on app.province_id = ref.province_id where applicant_type_id= {$person_type} group by app.province_id"));
    }
        

}