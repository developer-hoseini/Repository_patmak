<?php

namespace App\Repositories\Interfaces;

Interface RefLicenseTypeRepositoryInterface {

    public function getAll();

    public function find($license_type_id);
}