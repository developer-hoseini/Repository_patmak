<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefProvinceRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefProvinceRepository implements RefProvinceRepositoryInterface{

    public function getAll(){
        return DB::table('ref_provinces')->get();
    }

    public function find($province_id)
    {
        return DB::table('ref_provinces')->where('province_id', $province_id)->first();
    }

}