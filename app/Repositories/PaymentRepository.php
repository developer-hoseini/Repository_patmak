<?php

namespace App\Repositories;

use App\Models\PaymentModel;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use App\Repositories\Interfaces\RefCostRepositoryInterface;
use Illuminate\Support\Facades\DB;
use stdClass;

class PaymentRepository implements PaymentRepositoryInterface{

    protected $ref_cost_repository;

    public function __construct(RefCostRepositoryInterface $ref_cost_repository)
    {
        $this->ref_cost_repository = $ref_cost_repository;
    }

    // ---------------------------------------------------------------------------------------------------

    public function getPaymentsList($application, $khazane_cost, $commission_cost, $gtp_id) 
    {
        $is_payed = $this->checkIfPayedByAppId($application->application_id, $gtp_id);
        $p1 = new stdClass;
        $p1->title = 'هزینه صدور پروانه اشتغال به کار اعضای نظام مهندسی/کاردانی ساختمان استان ها';
        $p1->status = $is_payed ? 'پرداخت شده' : 'در انتظار پرداخت';
        $p1->amount = $khazane_cost;
        $p1->is_khazaneh = 1;
        $p1->is_payed = ($is_payed)? 1: 0;

        $is_payed = $this->checkIfPayedByAppId($application->application_id, 0);
        $p2 = new stdClass;
        $p2->title = 'کارمزد';
        $p2->status = $is_payed ? 'پرداخت شده' : 'در انتظار پرداخت';
        $p2->amount = $commission_cost;
        $p2->is_khazaneh = 0;
        $p2->is_payed = ($is_payed)? 1: 0;

        return [
            // $p1, $p2
            $p1
        ];
    }

    // ---------------------------------------------------------------------------------------------------

    public function findByOrderId($order_number)
    {
        return DB::table('tbl_payments')->where('order_number', $order_number)->first();
    }

    // ---------------------------------------------------------------------------------------------------

    public function checkIfPayedByAppId($application_id, $gtp_id = 0)
    {        
        return DB::table('tbl_payments')
            ->where('application_id', $application_id)
            ->where('gtp_id', $gtp_id)
            ->where('status_id', 2)
            ->first();        
    }

    // ---------------------------------------------------------------------------------------------------

    public function create($data)
    {
        $record = new PaymentModel($data);              
        $record->application_id = $data['application_id'];
        $record->user_id = $data['user_id'];
        $record->amount = $data['amount'];
        $record->order_number = $data['order_number'];
        $record->gtp_id = $data['gtp_id'];
        $record->status_id = $data['status_id'];

        $record->save();
        return $record->id;
    }

    // ---------------------------------------------------------------------------------------------------

    public function makeOrderId($application_id, $is_khazaneh = 0)
    {
        return date("ymdHis") . $is_khazaneh . $application_id;
    }

    // ---------------------------------------------------------------------------------------------------

    public function updateByOrderNumber($order_number ,$data)
    {
        $record = $this->findByOrderId($order_number);
        if( ! $record ) {
            return false;
        }

        $affected = DB::table('tbl_payments')
              ->where('id', $record->id)
              ->update($data);

        return $affected;
    }

    // ---------------------------------------------------------------------------------------------------

    public function findGtpPayedRecordByApplication($application_id)
    {
        return DB::table('tbl_payments')
            ->where('application_id', $application_id)
            ->where('gtp_id', '<>', 0) // for gtp payment gtp_id is not zero
            ->where('status_id', 2) // 2 means successful payment
            ->first(); 
    }

    // ---------------------------------------------------------------------------------------------------

    public function findGtpPayedRecordByOrderNumber($order_number)
    {
        return DB::table('tbl_payments')
            ->where('order_number', $order_number)
            ->where('gtp_id', '<>', 0) // for gtp payment gtp_id is not zero
            ->where('status_id', 2) // 2 means successful payment
            ->first(); 
    }

    // ---------------------------------------------------------------------------------------------------

    public function findNonGtpPayedRecordByApplication($application_id)
    {
        return DB::table('tbl_payments')
            ->where('application_id', $application_id)
            ->where('gtp_id', 0) // for non gtp (commission) payment gtp_id is zero
            ->where('status_id', 2) // 2 means successful payment
            ->first(); 
    }

    // ---------------------------------------------------------------------------------------------------

    public function countPayedApplications($person_type = 0)
    {
        $query = DB::table('tbl_payments')
            ->select(DB::raw('COUNT(tbl_application.application_id) as count'))
            ->leftJoin('tbl_application', 'tbl_payments.application_id', '=', 'tbl_application.application_id')
            ->where('tbl_payments.status_id', 2);

        if($person_type == 1 || $person_type == 2) {
            $query->where('tbl_application.applicant_type_id', $person_type);
        }
        
        return $query->first(); 
    }
}