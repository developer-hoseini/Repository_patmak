<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefLicenseTypeRepositoryInterface as InterfacesRefLicenseTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefLicenseTypeRepository implements InterfacesRefLicenseTypeRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_license_type')->where('deleted_at','=',NULL)->get();
    }

    public function find($license_type_id)
    {
        return DB::table('ref_license_type')->where('license_type_id', $license_type_id)->where('deleted_at','=',NULL)->first();
    }
}
