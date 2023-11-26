<?php

namespace App\Repositories;

use App\Models\AdministratorModel;
use App\Repositories\Interfaces\AdministratorRepositoryInterface;
use Illuminate\Support\Facades\DB;

class AdministratorRepository implements AdministratorRepositoryInterface{

    public function findByAdminId($admin_id)
    {
        return DB::table('tbl_administrators')
            ->select('*')
            ->where('admin_id' ,$admin_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------
    
    public function findByUsername($username)
    {
        return DB::table('tbl_administrators')
        ->select('*')
        ->where('username', $username)
        ->first();
    }
    
    // ---------------------------------------------------------------------------------------------------

    public function create($data)
    {               
        $admin = AdministratorModel::create($data);
        return $admin->admin_id;
    }

    // ---------------------------------------------------------------------------------------------------

    public function update($admin_id, $updates)
    {
        return AdministratorModel::find($admin_id)->update($updates);
        
    }

    // ---------------------------------------------------------------------------------------------------
}