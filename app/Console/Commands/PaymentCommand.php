<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\PaymentFacade;
use Illuminate\Support\Facades\DB;

class PaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment Update';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = DB::table("tbl_payments")
            ->where('rrn','!=',NULL)
            ->where('order_number','!=',NULL)
            ->where('transaction_id','!=',NULL)
            ->where('status_id','=','-1')
//            ->where('message','!=','زمان مود نظر به پایان رسیده و تراکنش منتقضی شده است')
            ->where('message','!=','Revoke')
            ->where('created_at','>=',date('Y-m-d 00:00:00'))
            ->get();

//        dump($transactions);
//        die();
//        dump($transactions);
//        die();

        foreach ($transactions as $transaction) {
            $data = [
                'transaction_id' => $transaction->transaction_id,
                'order_id' => $transaction->order_number
            ];
            $res = PaymentFacade::advancedQuery($data);

            if ($res['status']) {
                // چوابی از بانک دریافت شده
                $data = $res['data'];
                $transaction_status = $res['data']->status;
                if ($transaction_status === 'Settle') {
                    // تراکنش موفق بوده پس وضعیت رکورد در جداول را تغغیر بده

                    // اگر جدول پرداخت ها نیاز به تغییر دارد آن را تغییر بده
                    if ($transaction->status_id != 2) {
                        $affected = DB::table('tbl_payments')
                            ->where('order_number', $transaction->order_number)
                            ->update(['rrn' => $data->rrn, 'trace' => $data->trace, 'status_id' => 2, 'message' => $data->status]);
                    }

                    // وضعیت پرونده در جدول درخواست ها را نیز تغییر بده
                    $affected = DB::table('tbl_application')
                        ->where('application_id', $transaction->application_id)
                        ->update(['status_id' => 3]);

                } else {
                    if ($transaction->status_id != 2) {
                        $affected = DB::table('tbl_payments')
                            ->where('order_number', $transaction->order_number)
                            ->update(['message' => $data->status]);
                    }
                }

            }
        }
    }
}
