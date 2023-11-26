<?php

namespace App\Repositories\Interfaces;

Interface LogSabteAhvalRepositoryInterface {

    public function find($person_national_code);

    public function create($data);
}