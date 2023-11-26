<?php

namespace App\Services;

use App\Repositories\Interfaces\LogSabteAhvalRepositoryInterface;
use stdClass;

class FakeSabteahval{

    protected $logSabteAhvalRepository;

    public function __construct(LogSabteAhvalRepositoryInterface $logSabteAhvalRepositoryInterface)
    {
        $this->logSabteAhvalRepository = $logSabteAhvalRepositoryInterface;
    }


    public function getInfo($ncode, $birthcode){
        $person['birthDate'] = "1368/09/22";
        $person['bookNo'] = 0;
        $person['bookRow'] = 0;
        $person['deathStatus'] = 0;
        $person['family'] = "پوستيني";
        $person['fatherName'] = "محمدصادق";
        $person['gender'] = "1";
        $person['message'] = "";
        $person['name'] = "محمدمهدي";
        $person['nin'] = "11277191";
        $person['officeCode'] = "328";
        $person['shenasnameNo'] = 0;
        $person['shenasnameSeri'] = "ا30";
        $person['shenasnameSerial'] = "310969";
        $person['deathDate'] = 0;
        $person['exceptionMessage'] = 0;

        $this->logSabteAhvalRepository->create($person);

        return ['status' => true, 'message' => '', 'data' => $person];
    }
}