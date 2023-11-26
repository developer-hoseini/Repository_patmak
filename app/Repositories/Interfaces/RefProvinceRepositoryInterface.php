<?php

namespace App\Repositories\Interfaces;

Interface RefProvinceRepositoryInterface{

    public function getAll();

    public function find($province_id);

}