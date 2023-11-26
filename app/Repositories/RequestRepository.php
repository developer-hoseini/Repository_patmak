<?php

namespace App\Repositories;

use App\Models\RequestModel;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RequestRepository implements RequestRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data) {

        $record = RequestModel::where('application_id' ,$application_id)->first();
        if(! $record ){
            $record = new RequestModel();
        }        
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->req_mrud_org_id = $data['req_mrud_org_id'];
        $record->req_province_id = $data['req_province_id'];
        $record->req_membership_no = $data['req_membership_no'];
        $record->req_anjoman_membership_no = $data['req_anjoman_membership_no'];
        $record->req_license_type_id = $data['req_license_type_id'];
        $record->req_type_id = $data['req_type_id'];
        
        if($record->save()){
            return $record->req_id;
        } else {
            return false;
        }

    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_request_info AS req')
            ->select('license_type_title','morg_title', 'province_title','req_membership_no','req_type_title')
            ->leftJoin('ref_provinces AS p', 'req.req_province_id', '=', 'p.province_id') // To get province title
            ->leftJoin('ref_mrud_organizations AS morg', 'req.req_mrud_org_id', '=', 'morg.morg_id') // To get Organization title
            ->leftJoin('ref_license_type AS lic', 'req.req_license_type_id', '=', 'lic.license_type_id') // To get License Type Title
            ->leftJoin('ref_request_type AS ret', 'req.req_type_id', '=', 'ret.req_type_id') // To get Request Type Title
            ->where('application_id' ,$app_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_request_info')
            ->select("req_membership_no")
            ->where('user_id', $user_id)
            ->orderBy("req_id", 'DESC')
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

}