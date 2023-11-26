<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface{

    public function findByUserId($user_id)
    {
        return DB::table('tbl_users AS users')
            ->select('*')
            ->where('user_id' ,$user_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Searchs for a user matches with given $national_code.
     * 
     * @param string $national_code National Code of the person
     */
    public function findByNationalCode($national_code)
    {
        return DB::table('tbl_users AS users')
            ->select('*')
            ->where('ncode' ,$national_code)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($data) 
    {
        $record = new UserModel();    
        $record->mobile = $data['mobile'];
        $record->ncode = $data['ncode'];
        $record->birthdate = $data['birthdate'];
        $record->person_type_id = $data['person_type_id'];
        $record->org_code = $data['org_code'];

        if($record->save()){
            return $record;
        } else {
            return false;
        }
    }    

    // ---------------------------------------------------------------------------------------------------

    public function updateMobile($user_id, $mobile)
    {
        $flight = UserModel::find($user_id);
        $flight->mobile = $mobile;
        $flight->save();
        //return DB::table('tbl_users')->where('id', $user_id)->update(['mobile' => $mobile]);
    }

    public function updateData($user_id, $data)
    {
        $flight = UserModel::find($user_id);
        $flight->birthdate = $data['birthdate'];
        $flight->mobile = $data['mobile'];
        $flight->org_code = $data['org_code'];
        $flight->person_type_id = $data['person_type_id'];
        $flight->save();
        //return DB::table('tbl_users')->where('id', $user_id)->update(['mobile' => $mobile]);
    }
}