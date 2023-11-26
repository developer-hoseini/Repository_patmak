<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\SendSms;

class TestController extends Controller
{
    use SendSMS;

    public function index()
    {
        dump($this->curlrequest('https://sr-tuix.mrud.ir/services/PostAPI/Token'));
        die();
    }

    public function curlrequest($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS,
            "username=test&password=cba123abc@&grant_type=password");

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/x-www-form-urlencoded; charset=utf-8",
            "Authorization:Basic ".base64_encode('test:cba123abc@')
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return json_decode($resp,true);
    }

//    protected function _message($mobile,$message)
//    {
//        SendSMS::dispatch($mobile,$message);
//    }
}
