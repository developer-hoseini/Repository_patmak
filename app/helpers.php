<?php

use App\Facades\AuthAdminFacade;

if(!function_exists('mask_mobile')){
    function mask_mobile($mobile){
        return substr_replace($mobile, str_repeat("X", 4), 4, 4);
    }
}


if(!function_exists('calculate_trackingcode')){

    /**
     * This function calculates tracking code base on application id and day of creation.
     * @param $app_id int Primary key of application
     * @param $app_creation_datetime string a date ztring format like : 2022-01-01
     * @return string
     */
    function calculate_trackingcode($app_id, $app_creation_datetime){
        // Convert string date time to int
        $datetime = strtotime($app_creation_datetime);
        // Get year 2 character number
        $year = date("y", $datetime);
        // Get day number of year 0 to 365
        $day_of_year = date("z", $datetime);
        // Day of year musb be 3 in length, Make it 3 charcters of not
        if(strlen($day_of_year) === 2){
            $day_of_year = "0" . $day_of_year;
        } elseif (strlen($day_of_year) === 1) {
            $day_of_year = "00" . $day_of_year;
        }
        $trackingcode = "{$year}{$day_of_year}{$app_id}";
        return $trackingcode;
    }
}

if(!function_exists('get_admin_name')){

    /**
     * Returns name oif admin
     * @return string
     */
    function get_admin_name(){
      

        if(AuthAdminFacade::isLoggedIn()) {
            return '(' . AuthAdminFacade::getAdminName() . ')';
        } else {
            return '';
        }
    }
}


if(!function_exists('numEnToFa')){

    function numEnToFa($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            
        $output= str_replace($english, $persian, $string);
        return $output;
    }
}

if (!function_exists('fgc_request')) {
    function fgc_request($url,$method)
    {
        $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false
            ],
            'http' => [
                'method' => $method,
                'header' => "Content-type: application/json\r\n" ."Accept: application/json\r\n" ."Cache-Control: no-cache, must-revalidate\r\n"."X-API-KEY:g4iv0IfGQZ1tzEPSFj6lMTZf\r\n"."Authorization:".base64_encode('patmak.mrud.ir:o1H10UryEhi9xyBc')."\r\n"
            ]
        ];

        return $options;

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return json_decode($result,true);
    }
}

