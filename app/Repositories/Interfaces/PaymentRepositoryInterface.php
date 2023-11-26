<?php

namespace App\Repositories\Interfaces;

Interface PaymentRepositoryInterface{

    public function getPaymentsList($aplication_id, $khazane_cost, $commission_cost, $gtp_id);
    
    public function create($data);

    public function findByOrderId($order_number);

    public function makeOrderId($application_id, $is_khazaneh);

    public function updateByOrderNumber($order_number ,$data);

    public function checkIfPayedByAppId($application_id, $gtp_id = null);

    /**
     * Finds and returs details of payed License cost (GTP) record   of an Application.
     * @param $appliation_id
     */
    public function findGtpPayedRecordByApplication($application_id);

    public function findGtpPayedRecordByOrderNumber($order_number);

    /**
     * Finds and returs details of payed commission (non GTP) record   of an Application.
     * @param $appliation_id
     */
    public function findNonGtpPayedRecordByApplication($application_id);

    /**
     * Returns number of payed applications in total, legal or regualr persons.
     */
    public function countPayedApplications($person_type = 0);
}