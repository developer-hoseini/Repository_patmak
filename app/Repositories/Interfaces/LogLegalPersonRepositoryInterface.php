<?php

namespace App\Repositories\Interfaces;

Interface LogLegalPersonRepositoryInterface {

    public function find($organization_national_code);

    public function create($data);
}