<?php

namespace App\Repositories\Interfaces;

Interface RefGtpRepositoryInterface {

    public function getByPersonTypeIdAndProvinceId($person_type_id, $province_type_id);
}