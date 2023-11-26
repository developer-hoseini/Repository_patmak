<?php

namespace App\Repositories;

use App\Models\LogAdminModel;
use App\Repositories\Interfaces\LogAdminRepositoryInterface;

class LogAdminRepository implements LogAdminRepositoryInterface {

    public function create($data)
    {
        $record = new LogAdminModel();              
        $record->admin_id = $data['admin_id'];
        $record->action = $data['action'];
        $record->ip = $data['ip'];
        $record->data = isset($data['data'])? $data['data']: NULL;
        $record->message = isset($data['message'])? $data['message']: NULL;
        $record->create_at = date("Y-m-d H:i:s");
        $record->save();
        return $record->id;
    }
}