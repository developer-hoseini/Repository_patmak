<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefLicenseAuthRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefLicenseAuthRepository implements RefLicenseAuthRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_license_authentication')->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByLicenseTypeId($license_type_id)
    {
        return DB::table('ref_license_authentication')
            ->select('lic_auth_id', 'lic_auth_title')
            ->where('license_type_id', $license_type_id)
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------
}