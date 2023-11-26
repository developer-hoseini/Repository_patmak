<?php

namespace App\Repositories;

use App\Models\InsuranceModel;
use App\Repositories\Interfaces\InsuranceRepositoryInterface;
use Illuminate\Support\Facades\DB;

class InsuranceRepository implements InsuranceRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data) {

        $record = InsuranceModel::where('application_id' ,$application_id)->first();
        if(! $record ){
            $record = new InsuranceModel();
        }
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->ins_available = $data['ins_available'];
        $record->ins_type = $data['ins_type'];
        $record->ins_pay_location = $data['ins_pay_location'];
        $record->ins_main_occupation = $data['ins_main_occupation'];
        return $record->save();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_insurance_info')
            ->select('ins_available','ins_main_occupation','ins_pay_location','ins_type')
            ->where('application_id' ,$app_id)->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_insurance_info')
            ->select("ins_available","ins_main_occupation","ins_pay_location","ins_type")
            ->where('user_id', $user_id)
            ->orderBy("ins_id", 'DESC')
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------
}