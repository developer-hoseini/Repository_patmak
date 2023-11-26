<?php

namespace App\Repositories;

use App\Models\ContactInfoModel;
use App\Repositories\Interfaces\ContactInfoRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ContactInfoRepository implements ContactInfoRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data) {

        $record = ContactInfoModel::where('application_id' ,$application_id)->first();
        if(! $record ){
            $record = new ContactInfoModel();
        }        
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->mobile = $data['mobile'];
        $record->email = $data['email'];
        $record->tel_zone = $data['tel_zone'];
        $record->tel_number = $data['tel_number'];
        $record->postal_code = $data['postal_code'];
        $record->province_id = $data['province_id'];
        $record->city_id = $data['city_id'];
        $record->street1 = $data['street1'];
        $record->street2 = $data['street2'];
        $record->no = $data['no'];
        $record->floor = $data['floor'];
        $record->unit = $data['unit'];
        $record->work_address = $data['work_address'];
        $record->work_tel_number = $data['work_tel_number'];
        
        return $record->save();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_contact_info AS info')
            ->select('city_title','email','floor','mobile','no','postal_code','province_title','street1','street2','tel_number','tel_zone','unit')
            ->leftJoin('ref_provinces AS p', 'info.province_id', '=', 'p.province_id')
            ->leftJoin('ref_cities AS c', 'info.city_id', '=', 'c.city_id')
            ->where('application_id' ,$app_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_contact_info')
            ->select("city_id","email","floor","mobile","no","postal_code","province_id","street1","street2","tel_number","tel_zone","unit", "work_address", "work_tel_number")
            ->where('user_id', $user_id)
            ->orderBy("contact_id", 'DESC')
            ->first();
    }
}