<?php

namespace App\Repositories\Interfaces;

Interface RefLicenseBasisRepositoryInterface {

    public function getAll();

    public function getByLicenseTypeId($license_type_id);
}