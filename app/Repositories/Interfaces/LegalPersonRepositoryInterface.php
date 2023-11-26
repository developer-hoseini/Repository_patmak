<?php

namespace App\Repositories\Interfaces;

Interface LegalPersonRepositoryInterface{

    public function createOrUpdate($aplication_id, $data);

    public function find($id);

    public function getByAppId($app_id);
}