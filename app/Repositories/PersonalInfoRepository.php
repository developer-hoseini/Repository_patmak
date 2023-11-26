<?php

namespace App\Repositories;

use App\Models\PersonInfoModel;
use App\Repositories\Interfaces\PersonalInfoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class PersonalInfoRepository implements PersonalInfoRepositoryInterface{

    public function find($id){
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data){

        $person = PersonInfoModel::find($application_id);
        if(! $person ){
            $person = new PersonInfoModel();
        }        
        $person->user_id = $data['user_id'];
        $person->application_id = $data['application_id'];
        $person->p_name = $data['p_name'];
        $person->p_lname = $data['p_lname'];
        $person->p_ncode = $data['p_ncode'];
        $person->p_birth_date = $data['p_birth_date'];
        $person->p_certificate_id = $data['p_certificate_id'];
        $person->p_gender_id = $data['p_gender_id'];
        $person->p_fname = $data['p_fname'];
        $person->p_birth_location = $data['p_birth_location'];
        $person->p_marriage_status_id = $data['p_marriage_status_id'];
          
        return $person->save();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_person_info AS p')
            ->select('p_birth_date','p_birth_location','p_certificate_id','p_fname','p_gender_id','p_lname','p_marriage_status_id','p_name','p_ncode','province_title')
            ->leftJoin('ref_provinces AS pr', 'p.p_birth_location', '=', 'pr.province_id')
            ->where('application_id' ,$app_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_person_info')
            ->select("p_birth_location", "p_gender_id")
            ->where('user_id', $user_id)
            ->orderBy("p_id", 'DESC')
            ->first();
    }
}