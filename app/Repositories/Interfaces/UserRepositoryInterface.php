<?php

namespace App\Repositories\Interfaces;

Interface UserRepositoryInterface{

    public function findByUserId($user_id);

    public function create($data);

    public function findByNationalCode($national_code);

    public function updateMobile($user_id, $data);
}