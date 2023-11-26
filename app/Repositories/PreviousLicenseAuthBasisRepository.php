<?php

namespace App\Repositories;

use App\Models\PreviousLicenseAuthBasisModel;
use App\Repositories\Interfaces\PreviousLicenseAuthBasisRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PreviousLicenseAuthBasisRepository implements PreviousLicenseAuthBasisRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($appliaction_id, $data) {
        $record = new PreviousLicenseAuthBasisModel();      
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->prev_license_id = $data['prev_license_id'];
        $record->plic_auth = $data['plic_auth'];
        $record->plic_basis = $data['plic_basis'];
        $record->plic_auth_date = $data['plic_auth_date'];
        
        return $record->save();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Deletes all rcords correspondinf to given application_id
     */
    public function deleteAll($appliaction_id){
        $previous_records = DB::table('tbl_prev_license_auth_basis')
            ->where('application_id', $appliaction_id)
            ->get();

        foreach($previous_records as $record){
            PreviousLicenseAuthBasisModel::find($record->plic_auth_id)->delete();
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_prev_license_auth_basis AS pa')
            ->select('lic_auth_title','lic_basis_title','plic_auth_date')
            ->leftJoin('ref_license_basis AS ref1', 'pa.plic_basis', '=', 'ref1.lic_basis_id')
            ->leftJoin('ref_license_authentication AS ref2', 'pa.plic_auth', '=', 'ref2.lic_auth_id')
            ->where('application_id' ,$app_id)
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        // This query helps to find application id of last tbl_prev_license_auth_basis record.
        // As there are serveral records afor each application we need it to fetch out only records of last application.
        $raw = "(SELECT application_id FROM tbl_prev_license_auth_basis WHERE user_id = {$user_id} ORDER BY plic_auth_id DESC LIMIT 1)";

        return DB::table('tbl_prev_license_auth_basis AS plab')
            ->select('plab.plic_auth_date','ref1.lic_auth_id', 'ref1.lic_auth_title', 'ref2.lic_basis_id', 'ref2.lic_basis_title')
            ->leftJoin('ref_license_authentication AS ref1', 'plab.plic_auth', '=', 'ref1.lic_auth_id')
            ->leftJoin('ref_license_basis AS ref2', 'plab.plic_basis', '=', 'ref2.lic_basis_id')
            ->where('user_id', $user_id)
            ->where('application_id', DB::raw($raw)) 
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------
}