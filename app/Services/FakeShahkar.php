<?php

namespace App\Services;


class FakeShahkar {

    protected $logLegalPersonRepository;

    public function check_matching($ncode, $mobile){
        
        return ['status' => true, 'message' => '', 'data' => ['matched' => true]];
    }
}