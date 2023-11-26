<?php

namespace App\Repositories\Interfaces;

Interface RefCityRepositoryInterface{

    public function getAll();

    public function getProvinceCities($province_id);

}