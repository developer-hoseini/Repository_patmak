<?php

namespace App\Repositories\Interfaces;

Interface RefCostRepositoryInterface {

    public function getByPersonTypeIdAndRequestTypeId($person_type_id, $request_type_id);

    /**
     * Return an array of prices foreach request and person type.     
     */
    public function getPriceList();
}