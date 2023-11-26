<?php

namespace App\Repositories\Interfaces;

Interface EducationRepositoryInterface{

    public function create($appliction_id, $data);

    public function find($id);

    public function deleteAll($appliction_id);

    public function getByAppId($app_id);

    public function getUserLastestRawData($user_id);    
}