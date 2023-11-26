<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefCityRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefCityRepository implements RefCityRepositoryInterface{

    public function getAll(){
        return DB::table('ref_cities')->get();
    }

    public function getProvinceCities($province_id)
    {
        return DB::table('ref_cities')->where('province_id', $province_id)->get();
    }

}