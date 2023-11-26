<?php

namespace App\Http\Controllers;

use App\Rules\ValidPersonNationalCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class Api extends Controller
{
    protected $errors;
    protected $messages;

    public function __construct()
    {
        $this->errors = config('api.errors');
        $this->messages = config('api.messages');
    }

    /**
     * Get token via username and password
     */
    public function authenticate(Request $request)
    {
        try{
            // validation
            $validator = Validator::make($request->all(), [
                'username' => ['required'],
                'password' => ['required']
            ]);

            if ($validator->fails()) {
                return response()->apiRes(null, $this->errors['1001'], false, 1001);
            }

            $input_username = $request->input('username');
            $input_password = $request->input('password');

            $users = config('api.users');

            if( ! isset($users[$input_username]) ) {
                return response()->apiRes(null, $this->errors['1002'], false, 1002);
            }

            // Check if user password
            $user = $users[$input_username];
            $real_password = $user['password'];
            if( $input_password !== $real_password ) {
                return response()->apiRes(null, $this->errors['1003'], false, 1003);
            }

            // Check if user is active
            $is_user_active = $user['active'];
            if( ! $is_user_active ) {
                return response()->apiRes(null, $this->errors['1007'], false, 1007);
            }
            // generate a random token string
            $access_token = Str::random(60);
            $expires_in = config('api.access_token.expire_time');
            // save token in cache
            cache([$access_token => $expires_in], $expires_in);

            return response()->apiRes(['access_token' => $access_token, 'expires_in' => $expires_in], $this->messages['OK'], true, 0);
        } catch (\Exception $e) {
            Log::error('API ERROR: Class ' . __CLASS__ . ' | Method: ' . __METHOD__ . ' | Message: ' . $e->getMessage());
            return response()->apiRes(null, $this->errors['1006'], true, 1006);
        }
    }

    /**
     * Get tranactions of given ncode
     */
    public function transactions(Request $request)
    {
        try{
            // validation
            $validator = Validator::make($request->all(), [
                'user_type' => ['required', 'in:1,2'],
                'user_code' => ['required', new ValidPersonNationalCode]
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->apiRes(null, current($errors), false, 1004);
            }

            $user_type = $request->input('user_type');
            $user_code = $request->input('user_code');
            $from_date = $request->input('from_date');

            $query = DB::table('tbl_payments')
                ->select('app_type_id', 'trace', 'amount', 'tbl_payments.created_at', 'user_person_ncode' , 'user_org_ncode', 'tbl_payments.status_id')
                ->leftJoin('tbl_application', 'tbl_payments.application_id', '=', 'tbl_application.application_id')
                ->where('tbl_payments.status_id' , 2); // 2 means successful payment

            if($user_type === 1){
                $query->where('tbl_application.user_person_ncode', '=', $user_code); // حقیقی
            } else {
                $query->where('tbl_application.user_org_ncode', '=', $user_code); // حقوقی
            }

            if($from_date){
                $query->where('tbl_payments.created_at', '>=', $from_date);
            }
            
            $transactions = $query->get();

            return response()->apiRes(['transactions' => $transactions], $this->messages['OK'], true, 0);
        } catch (\Exception $e) {
            Log::error('API ERROR: Class ' . __CLASS__ . ' | Method: ' . __METHOD__ . ' | Message: ' . $e->getMessage());
            return response()->apiRes(null, $this->errors['1006'], true, 1006);
        }
    }

    /**
     * Get transaction details of given trace
     */
    public function transactionByTrace(Request $request){
        try {
            // validation
            $validator = Validator::make($request->all(), [
                'trace' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                return response()->apiRes(null, current($errors), false, 604);
            }

            $trace = $request->input('trace');

            $query = DB::table('tbl_payments')
                ->select('app_type_id', 'trace', 'amount', 'tbl_payments.created_at', 'user_person_ncode' , 'user_org_ncode','tbl_payments.status_id')
                ->leftJoin('tbl_application', 'tbl_payments.application_id', '=', 'tbl_application.application_id')
                ->where('tbl_payments.trace' , $trace); 
            
            $transaction = $query->first();

            return response()->apiRes(['transaction' => $transaction], $this->messages['OK'], true, 0);
        } catch (\Exception $e) {
            Log::error('API ERROR: Class ' . __CLASS__ . ' | Method: ' . __METHOD__ . ' | Message: ' . $e->getMessage());
            return response()->apiRes(null, $this->errors['1006'], true, 1006);   
        }
    }
}