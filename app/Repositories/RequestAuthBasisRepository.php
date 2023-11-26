<?php

namespace App\Repositories;

use App\Models\RequestAuthBasisModel;
use App\Repositories\Interfaces\RequestAuthBasisRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RequestAuthBasisRepository implements RequestAuthBasisRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($appliaction_id, $data) {
        $record = new RequestAuthBasisModel();      
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->request_id = $data['request_id'];
        $record->license_auth_id = $data['license_auth_id'];
        $record->license_basis_id = $data['license_basis_id'];
        
        return $record->save();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Deletes all rcords correspondinf to given application_id
     */
    public function deleteAll($appliaction_id){
        $previous_records = DB::table('tbl_request_auth_basis')
            ->where('application_id', $appliaction_id)
            ->get();

        foreach($previous_records as $record){
            RequestAuthBasisModel::find($record->req_auth_id)->delete();
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_request_auth_basis AS ab')
            ->select('lic_auth_title', 'lic_basis_title')
            ->leftJoin('ref_license_basis AS ref1', 'ab.license_basis_id', '=', 'ref1.lic_basis_id')
            ->leftJoin('ref_license_authentication AS ref2', 'ab.license_auth_id', '=', 'ref2.lic_auth_id')
            ->where('application_id' ,$app_id)
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        // This query helps to find application id of last education id.
        // As there are serveral records afor each application we need it to fetch out only records of last application.
        $raw = "(SELECT application_id FROM tbl_request_auth_basis WHERE user_id = {$user_id} ORDER BY req_auth_id DESC LIMIT 1)";

        return DB::table('tbl_request_auth_basis AS rab')
            ->select("license_auth_id","license_basis_id", 'ref1.lic_auth_title', 'ref2.lic_basis_title')
            ->leftJoin('ref_license_authentication AS ref1', 'rab.license_auth_id', '=', 'ref1.lic_auth_id')
            ->leftJoin('ref_license_basis AS ref2', 'rab.license_basis_id', '=', 'ref2.lic_basis_id')
            ->where('user_id', $user_id)
            ->where('application_id', DB::raw($raw)) 
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------
}