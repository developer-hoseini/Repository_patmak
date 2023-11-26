<?php

namespace App\Repositories\Interfaces;

Interface RefLicenseAuthRepositoryInterface {

    public function getAll();

    public function getByLicenseTypeId($license_type_id);
}