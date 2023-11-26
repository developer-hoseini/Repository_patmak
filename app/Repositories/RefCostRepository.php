<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RefCostRepositoryInterface;
use App\Repositories\Interfaces\RefEducationGradeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class RefCostRepository implements RefCostRepositoryInterface{

    public function getByPersonTypeIdAndRequestTypeId($person_type_id, $request_type_id){
        return DB::table('ref_costs')
                ->where('person_type_id', $person_type_id)
                ->where('request_type_id', $request_type_id)
                ->first();
    }

    public function getPriceList()
    {
        $query = "SELECT rc.cost_id, rpt.person_type_title, rrt.req_type_title, rc.amount
                  FROM `ref_costs` rc
                  LEFT JOIN ref_person_type rpt ON rc.person_type_id = rpt.person_type_id
                  LEFT JOIN ref_request_type rrt ON rc.request_type_id = rrt.req_type_id
                  ORDER BY rc.cost_id;";
                  
        return DB::select(DB::raw($query));
    }


    
}