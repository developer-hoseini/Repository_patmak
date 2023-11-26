<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefLicenseBasisRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefLicenseBasisRepository implements RefLicenseBasisRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_license_basis')->get();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getByLicenseTypeId($license_type_id)
    {
        return DB::table('ref_license_basis')
            ->select('lic_basis_id','lic_basis_title')
            ->where('license_type_id', $license_type_id)
            ->get();
    }

    // ---------------------------------------------------------------------------------------------------
}