<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefMrudOrganizationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefMrudOraganizationRepository implements RefMrudOrganizationRepositoryInterface{

    public function getAll()
    {
        return DB::table('ref_mrud_organizations')->get();
    }
}