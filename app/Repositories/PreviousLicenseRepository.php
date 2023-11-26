<?php

namespace App\Repositories;

use App\Models\PreviousLicenseModel;
use App\Repositories\Interfaces\PreviousLicenseRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PreviousLicenseRepository implements PreviousLicenseRepositoryInterface{

    public function find($id) {
        // return User::find($id);
    }

    // ---------------------------------------------------------------------------------------------------

    public function createOrUpdate($application_id, $data) {

        $record = PreviousLicenseModel::where('application_id' ,$application_id)->first();
        if(! $record ){
            $record = new PreviousLicenseModel();
        }        
        $record->user_id = $data['user_id'];
        $record->application_id = $data['application_id'];
        $record->plic_no = $data['plic_no'];
        $record->plic_serial_no = $data['plic_serial_no'];
        $record->plic_date_first_issue = $data['plic_date_first_issue'];
        $record->plic_date_expire = $data['plic_date_expire'];
        
        if($record->save()){
            return $record->plic_id;
        } else {
            return false;
        }

    }

    // ---------------------------------------------------------------------------------------------------

    public function getByAppId($app_id)
    {
        return DB::table('tbl_prev_license_info')
            ->select('plic_date_expire','plic_date_first_issue','plic_date_last_renewal','plic_no','plic_serial_no')
            ->where('application_id' ,$app_id)
            ->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function getImages($app_id)
    {
        $path = storage_path('app/application/' . $app_id) ;
        if(File::exists($path)) {

            // Application has previous license file directory
            $files = File::files($path);               
            foreach($files as $file){
                if (strpos($file->getFilename() , 'front') !== false) {
                    $front = $file->getFilename();
                    $front_file_url = '/application/get-file?file=' . $app_id . '-' . $front;
                }
                if (strpos($file->getFilename() , 'rear') !== false) {
                    $rear = $file->getFilename() ;
                    $rear_file_url = '/application/get-file?file=' . $app_id . '-' . $rear;
                }
            } 
            
            return [
                'founded' => true,
                'front' => $front_file_url,
                'rear'  => $rear_file_url
            ];

        } else {

            // Application does not have previous license file directory
            return [
                'founded' => false,
                'front' => '/assets/img/no-image.png',
                'rear'  => '/assets/img/no-image.png'
            ];
        }
    }

    // ---------------------------------------------------------------------------------------------------

    public function getUserLastestRawData($user_id)
    {
        return DB::table('tbl_prev_license_info')
            ->select("plic_date_first_issue","plic_date_last_renewal","plic_date_expire","plic_serial_no", "plic_no")
            ->where('user_id', $user_id)
            ->orderBy("plic_id", 'DESC')
            ->first();
    }
}