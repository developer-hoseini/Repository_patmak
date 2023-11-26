<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefGtpRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefGtpRepository implements RefGtpRepositoryInterface{

    public function getByPersonTypeIdAndProvinceId($person_type_id, $province_type_id)
    {
        return DB::table('ref_gtp')
                ->where('person_type_id', $person_type_id)
                ->where('province_id', $province_type_id)
                ->first();
    }
    
}