<?php

namespace App\Repositories;

use App\Models\LogSabteAhvalModel;
use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use Illuminate\Support\Facades\DB;

class LogSabteAhvalRepository implements LogSabteAhvalRepositoryInterface{

    public function find($id){
        return DB::table('log_sabte_ahval')->where('ncode', $id)->first();
    }

    public function create($data)
    {
        $ncode = $data['nin'];
        $ncode_length = strlen($data['nin']);

        switch($ncode_length){
            case 8: 
                $ncode = '00' . $ncode;
                break;
            case 9: 
                $ncode = '0' . $ncode;
                break;
            default:
                $ncode = $ncode;
        }

        $bd = $data['birthDate'];
        $message = (is_string($data['message'])) ? $data['message']: json_encode($data['message']);
        $death_date = (is_string($data['deathDate'])) ? $data['deathDate']: json_encode($data['deathDate']);
        $exception_message = (is_string($data['exceptionMessage'])) ? $data['exceptionMessage']: json_encode($data['exceptionMessage']);

        $record = new LogSabteAhvalModel();              
        $record->ncode = $ncode;
        $record->name = base64_decode($data['name']);
        $record->lname = base64_decode($data['family']);
        $record->fname = base64_decode($data['fatherName']);
        $record->birthdate = substr($bd, 0, 4) . '/' . substr($bd, 4, 2) . '/' . substr($bd, 6, 2);
        $record->gender = $data['gender'];
        //$record->shenasnameh_seri = $data['shenasnameSeri'];
        $record->shenasnameh_serial = $data['shenasnameSerial'];
        $record->shenasnameh_no = $data['shenasnameNo'];
        $record->death_status = $data['deathStatus'];
        $record->death_date = $death_date;
        $record->message = $message;
        $record->book_no = $data['bookNo'];
        $record->book_row = $data['bookRow'];
        $record->exception_message = $exception_message;
        $record->created_at = date('Y-m-d H:i:s');

        $record->save();
        return $record->id;
    }

    public function update($id, $data){
        return $this->find($id)->update($data);
    }
}