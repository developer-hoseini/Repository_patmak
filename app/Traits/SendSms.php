<?php

namespace App\Traits;

trait SendSms
{
    private $url = 'https://sms.magfa.com/api/http/sms/v2';

    private function req($url, $method, $params = [])
    {
        // credentials
        $username = config('services.magfa.username');
        $password = config('services.magfa.password');
        $domain = config('services.magfa.domain');

        $url = $this->url . $url;

        if ($method == 'GET') {
            if(count($params) > 0) {
                $i = 0;
                foreach ($params as $key => $param) {
                    if ($i == 0) {
                        $url .= "?";
                    } else {
                        $url .= "&";
                    }

                    $url .= $key . '=' . $param;

                    $i++;
                }
            }
        }

        $curl = curl_init($url);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
        }

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json; charset=utf-8",
            'Authorization: Basic ' . base64_encode($username . "/" . $domain . ":" . $password)
        );

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        return json_decode(curl_exec($curl));
    }

    public function credit()
    {
        return $this->req('/balance', 'GET');
    }
}
