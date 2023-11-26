<?php

namespace App\Repositories\Interfaces;

Interface ApplicationRepositoryInterface{

    public function find($id);

    public function getAll();

    public function create($data);

    public function update($id, $data);

    public function getUserApplications($user_id);

    public function getUserSuccessApplications($user_id);

    public function getApplication($application_id);

    public function getUserLastestRawData($user_id);

    public function getApplicationByTrackingCode($tracking_code);

    /**
     * این متد درخواستهایی که توسط کاربران ثبت نهایی شده اند یعنی مقدار وضعیت آنها جز یک هستند را بر میگرداند
     * 
     * @param int $province_id 
     * @param int $applicant_ncode
     * @return array 
     */
    public function getApplications($length = 10, $start = 0,$conditions = null);

    public function getApplicationFullDetails($application_id);

    public function countPayedApplicationsBasedOnRequestType($request_type);
    
    public function countPayedApplicationsBasedOnProvince($person_type);

}