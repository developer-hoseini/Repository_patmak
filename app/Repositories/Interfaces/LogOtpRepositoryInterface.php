<?php

namespace App\Repositories\Interfaces;

Interface LogOtpRepositoryInterface {

    public function findByPhoneNumber($phone_number);

    public function createOrUpdate($data);

    public function increaseTries($record_id);
}