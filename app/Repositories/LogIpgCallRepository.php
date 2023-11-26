<?php

namespace App\Repositories;

use App\Models\LogIpgCallModel;
use App\Models\LogPaymentModel;
use App\Repositories\Interfaces\LogIpgCallRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LogIpgCallRepository implements LogIpgCallRepositoryInterface{

    public function findByOrderId($order_id)
    {
        return DB::table('log_ipg_call')->where('order_id' ,$order_id)->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($data)
    {
        $record = new LogIpgCallModel();              
        $record->order_id = $data['order_id'];
        $record->endpoint = $data['endpoint'];
        $record->request_body = $data['request_body'];
        $record->response_body = $data['response_body'];

        $record->save();
        return $record->id;
    }

    // ---------------------------------------------------------------------------------------------------

}

