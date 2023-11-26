<?php

namespace App\Repositories;

use App\Models\LogOtpModel;
use App\Repositories\Interfaces\LogOtpRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LogOtpRepository implements LogOtpRepositoryInterface{

    public function findByPhoneNumber($phone_number)
    {
        return DB::table('log_otp')->where('mobile' ,$phone_number)->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($data)
    {
        

        $record = LogOtpModel::updateOrCreate(
            // Search form conditions we define in this array
            ['mobile' => $data['mobile']],
            // Updates what we ask in this array
            [
                'mobile' => $data['mobile'],
                'otp_code' => $data['otp_code'], 
                'tries' => 0, 
                'expires_at' => $data['expires_at'], 
                'created_at' => $data['created_at'],
                'updated_at' => $data['updated_at'],                
           ]
        );

        return $record->id;
    }

    // ---------------------------------------------------------------------------------------------------

    public function increaseTries($record_id)
    {
        DB::table('log_otp')
            ->where('id', $record_id)
            ->update([
                'tries' => DB::raw('tries + 1'),
            ]);
    }
}

