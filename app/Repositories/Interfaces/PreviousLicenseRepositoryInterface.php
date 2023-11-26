<?php

namespace App\Repositories\Interfaces;

Interface PreviousLicenseRepositoryInterface{

    public function find($id);

    public function createOrUpdate($aplication_id, $data);

    public function getByAppId($app_id);

    public function getImages($app_id);

    public function getUserLastestRawData($user_id);
}