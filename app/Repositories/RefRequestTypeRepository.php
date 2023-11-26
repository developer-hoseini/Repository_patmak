<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefRequestTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefRequestTypeRepository implements RefRequestTypeRepositoryInterface{

    public function getAll(){
        return DB::table('ref_request_type')->get();
    }

    
}