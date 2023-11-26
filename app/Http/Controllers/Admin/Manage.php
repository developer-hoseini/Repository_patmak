<?php

namespace App\Http\Controllers\Admin;

use App\Facades\AuthAdminFacade;
use App\Facades\PaymentFacade;
use App\Facades\SabteAhvalFacade;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\AdministratorRepositoryInterface;
use App\Services\AuthAdmin;
use App\Services\Sabteahval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Manage extends Controller
{

    public function transactions(Request $request)
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return redirect()->route('admin-login');
         }

        $transactions = DB::table("tbl_payments")->where('application_id', $request->application_id)->get();
        return view('admin.recheck-payment', ['transactions' => $transactions]);
    }


    public function recheckPayment(Request $request)
    {
        if( ! AuthAdminFacade::isLoggedIn() ) {
            return redirect()->route('admin-login');
         }

        $transaction = DB::table("tbl_payments")
            ->where('transaction_id', $request->transaction_id)
            ->where('order_number', $request->order_number)
            ->first();

        if($transaction){
            $data = [
                'transaction_id' => $request->transaction_id,
                'order_id' => $request->order_number
            ];
            $res = PaymentFacade::advancedQuery($data);

            if($res['status']){
                // چوابی از بانک دریافت شده
                $data = $res['data'];
                $message = $data->description;
                $transaction_status = $res['data']->status;
                if($transaction_status === 'Settle') {
                    // تراکنش موفق بوده پس وضعیت رکورد در جداول را تغغیر بده

                    // اگر جدول پرداخت ها نیاز به تغییر دارد آن را تغییر بده
                    if($transaction->status_id != 2) {
                        $affected = DB::table('tbl_payments')
                        ->where('order_number', $transaction->order_number)
                        ->update(['rrn' => $data->rrn, 'trace' => $data->trace, 'status_id' => 2, 'message' => $data->status]);
                    }

                    // وضعیت پرونده در جدول درخواست ها را نیز تغییر بده
                        $affected = DB::table('tbl_application')
                        ->where('application_id', $transaction->application_id)
                        ->update(['status_id' => 3]);

                } else {
                    if($transaction->status_id != 2) {
                        $affected = DB::table('tbl_payments')
                        ->where('order_number', $transaction->order_number)
                        ->update(['message' => $data->status]);
                    }
                }

            } else {
                // جوابی نیامده
                $message = "پاسخ مورد انتظار از بانک دریافت نشد";
            }

            // dd($transaction_status, $res);
            return redirect("/admin/application/{$transaction->application_id}/transactions")->with('message', $message);
        } else {
            abort(404);
        }


    }

    // ---------------------------------------------------------------------------------------------------

    public function sabteAhvalCheck(Request $request)
    {
        $username = $request->input('u');
        if($username !== 'developer') {
            die('Insufficent authorization');
        }
        $ncode = $request->input('n');
        if( ! $ncode ) {
            die('Error1: Insufficent data.');
        }
        $birth_date = $request->input('b');
        if( ! $birth_date ) {
            die('Error2: Insufficent data.');
        }
        echo($ncode) . '<br>';
        echo($birth_date) . '<br>';
        echo 'Result:<br>';
        $person_info = SabteAhvalFacade::getInfo($ncode, $birth_date);
        if($person_info) {
            if(is_array($person_info)){
                foreach($person_info as $index => $item){
                    if(is_array($item)){
                        foreach($item as $__index => $__item){
                            if(is_string($__item)){
                                echo $__index . ' => ' . $__item . '<br>';
                            }
                        }
                    } else if(is_string($item)) {
                        echo $index . ' => ' . $item . '<br>';
                    }
                }
            } else {
                echo 'person info is not array.<br>';
            }
        } else {

            echo 'No data<br>.';

            var_dump($person_info);
        }
    }

    /**
     * @Temp function
     */
    public function storeTransactionIdToTblPayments()
    {
        exit;
        // $rows = DB::table("log_ipg_call")->offset(5000)->limit(1000)->get();
        // var_dump($rows);
        // echo 'ROWS:'. count($rows) .  '<br>';
        // foreach($rows as $row) {
        //     echo $row->order_id . ' => ' . $row->response_body . '<br>';
        //     $res = explode(',', $row->response_body);
        //     if(count($res) === 2) {
        //         echo '1. record has two parts.<br>';

        //         if($res[0] == '00') {
        //             echo '2. record is OK<br>';

        //             $payment = DB::table("tbl_payments")->where('order_number', $row->order_id)->first();
        //             if($payment){
        //                 echo '3. record has payment record.<br>';
        //                 $affected = DB::table('tbl_payments')
        //                 ->where('order_number', $row->order_id)
        //                 ->update(['transaction_id' => $res[1]]);
        //                 if($affected === 1) {
        //                     echo '4. record updated. <br>';
        //                 } else {
        //                     echo '<span style="background-color:red">4. record does not updated</span><br>';
        //                 }
        //             } else {
        //                 echo '<span style="background-color:red">3. record does not have payment record</span><br>';
        //             }
        //         } else {
        //             echo '<span style="background-color:red">2. record is NOK</span><br>';
        //         }
        //     } else {
        //         echo '<span style="background-color:red">1. record does have any parts</span><br>';
        //     }
        //     echo '-----------------------------------------------------------------------<br>';
        // }
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * @Temp function
     */
    public function addAdmin(AdministratorRepositoryInterface $adminRepo)
    {
        exit;
        $users = [
            // ['mafraz','3614864@mafraz','مهندس افراز','مدیر سایت'],
            // ['exp_sharqi','Arasbaran#123698','استان آذربایجان شرقی','کارشناس'],
            // ['exp_qarbi','Soleiman&56987','استان آذربایجان غربی','کارشناس'],
            // ['exp_ardebil','Soha%Lake%65412','استان اردبیل','کارشناس'],
            // ['exp_isfahan','33pol&&iran13','استان اصفهان','کارشناس'],
            // ['exp_alborz','Dizin*654789','استان البرز','کارشناس'],
            // ['exp_elam','Ahouran#32587','استان ایلام','کارشناس'],
            // ['exp_boushehr','Nayband&$987321','استان بوشهر','کارشناس'],
            // ['exp_tehran','Milad@%Tower654','استان تهران','کارشناس'],
            // ['exp_jonoubi','Azmiqan^5421^','استان خراسان جنوبی','کارشناس'],
            // ['exp_razavi','Ferdowsi%6321$','استان خراسان رضوی','کارشناس'],
            // ['exp_shomali','Esfarayen^5478&','استان خراسان شمالی','کارشناس'],
            // ['exp_khouzestan','Shoushtar%963$$','استان خوزستان','کارشناس'],
            // ['exp_zanjan','Khandaqlou^302010','استان زنجان','کارشناس'],
            // ['exp_semnan','Shahmirzad*613497%','استان سمنان','کارشناس'],
            // ['exp_sistan','Hamoun^^8090100','استان سیستان و بلوچستان','کارشناس'],
            // ['exp_fars','Pasargad%445566$$$','استان فارس','کارشناس'],
            // ['exp_qazvin','Kantour#99#88#77#','استان قزوین','کارشناس'],
            // ['exp_qom','Timcheh<95623014?','استان قم','کارشناس'],
            // ['exp_lorestan','Poldokhtar^205080&','استان لرستان','کارشناس'],
            // ['exp_mazandaran','Badab%Sourt^011','استان مازندران','کارشناس'],
            // ['exp_markazi','^Mahalat^625242^','استان مرکزی','کارشناس'],
            // ['exp_hormozgan','Chahkouh#685848#$','استان هرمزگان','کارشناس'],
            // ['exp_hamedan','Hegmataneh^*9899100^','استان همدان','کارشناس'],
            // ['exp_bakhtiari','Zamankhan&747576&','استان چهارمحال و بختیاری','کارشناس'],
            // ['exp_kordestan','Tangivar*12131415^','استان کردستان','کارشناس'],
            // ['exp_kerman','Mavidi1020^3040^','استان کرمان','کارشناس'],
            // ['exp_kermanshah','Bistoun$505152&','استان کرمانشاه','کارشناس'],
            // ['exp_boyerahmad','Kachsaran%%414243%%','استان کهگیلویه و بویراحمد','کارشناس'],
            // ['exp_golestan','Kavous#100#Gonbad','استان گلستان','کارشناس'],
            // ['exp_gilan','Chamkhaleh^805020*','استان گيلان','کارشناس'],
            // ['exp_yazd','Chakhmakh##908070##','استان یزد','کارشناس']
            // ['af.firouzi','V3z@ratR0@d','استان قم','کارشناس']
        ];

        foreach($users as $user){
            $type = 2;
            $name = $user[3] . ' ' . $user[2];
            $username = $user[0];
            $password = $user[1];
            $data = [
                'admin_name' => $name,
                'admin_type_id' => $type,
                'username' => $username,
                'password' => AuthAdmin::hash_password($username, $password ),
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'failed_logins' => 0
            ];
            $res = $adminRepo->create($data);

        }
        // $type = 2;
        // $name = 'Afraz';
        // $username = 'mafraz';
        // $password = '3614864@mafraz';


        // dd($res);

    }

    public function check(){

        exit;
        $rows = DB::table("tbl_application")
            ->where('tbl_application.status_id', 3)
            ->where('application_id', '>', 5985)
            // ->offset(3000)->limit(2000)
            ->get();

            echo 'ROW: ' . count($rows) . '<br>';

        foreach($rows as $row){
            $pays = DB::table("tbl_payments")
            ->where('application_id', $row->application_id)
            ->get();

            $is_payed = false;
            foreach($pays as $pay){
                if($pay->status_id == 2){
                    $is_payed = true;
                    continue;
                }
            }

            if($is_payed){
                echo "application {$row->application_id} is payed. <br>";
                $is_payed = true;
                continue;
            } else {
                echo "<p style=\"color:red\">application {$row->application_id} is NOT payed.</p> <br>";
            }
        }

        // dd($rows);
    }
}