<?php

namespace App\Repositories\Interfaces;

Interface AdministratorRepositoryInterface {

    public function findByAdminId($admin_id);

    public function findByUsername($admin_id);

    public function create($admin_data);

    public function update($admin_id, $updates);
}