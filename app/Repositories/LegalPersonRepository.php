<?php

namespace App\Repositories;

use App\Models\LegalPersonModel;
use App\Repositories\Interfaces\LegalPersonRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LegalPersonRepository implements LegalPersonRepositoryInterface{

    public function find($id){
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data){

        $legal_person = LegalPersonModel::where('application_id' ,$application_id)->first();
        if(! $legal_person ){
            $legal_person = new LegalPersonModel();
        }        
        $legal_person->user_id = $data['user_id'];
        $legal_person->application_id = $data['application_id'];
        $legal_person->org_name = $data['org_name'];
        $legal_person->org_ncode = $data['org_ncode'];
        $legal_person->org_reg_number = $data['org_reg_number'];
        $legal_person->org_reg_date = $data['org_reg_date'];
        $legal_person->org_establishment_date = $data['org_estblishment_date'];
        return $legal_person->save();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_org_info')
            ->select('org_establishment_date','org_name','org_ncode','org_reg_date','org_reg_number')
            ->where('application_id' ,$app_id)->first();
    }
}