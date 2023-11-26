<?php

namespace App\Http\Controllers;

use App\Facades\SabteAhvalFacade;
use App\Facades\SabteAsnadFacade;
//use App\Jobs\SendSMS;
use App\Services\Sabteasnad;
use Illuminate\Http\Request;
//use App\Traits\SendSms;

class Home extends Controller
{
    //use SendSMS;

    public function index() {
        return view('home.new');
    }

    public function newhome() {
        return view('home.index');
    }

    public function asd(){
        // $organ_info = SabteAsnadFacade::inquirySpecialByNationalCode('14003724366');
       //  dd($organ_info);
       dd('aaaaa');
	    // $person_info = SabteAhvalFacade::getInfo('0011277191', '13680922');
    //    dd($person_info, '555');
       dd('dddddddd');
    }

    public function testSms()
    {
        dd($this->credit());
    }

//    protected function _message($mobile,$message)
//    {
//        SendSMS::dispatch($mobile,$message);
//    }
}
