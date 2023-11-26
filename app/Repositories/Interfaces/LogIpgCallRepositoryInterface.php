<?php

namespace App\Repositories\Interfaces;

Interface LogIpgCallRepositoryInterface {

    public function findByOrderId($order_id);

    public function create($data);
}