<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\ApplicationRepositoryInterface;
use App\Repositories\Interfaces\PaymentRepositoryInterface;
use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class PaymentValidation extends Controller
{
    
    public function index()
    {
        return view('payment_validation.index');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Captcha reference : https://github.com/mewebstudio/captcha
     */
    public function submit(Request $request, ApplicationRepositoryInterface $appRepo, PaymentRepositoryInterface $payRepo)
    {
        // Validation
        $rules = [
            'captcha' => 'required|captcha',
            'trackingcode' => 'required|numeric',
            'orderid' => 'required|numeric',
        ];

        $validator = validator()->make($request->all(), $rules);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return back()->withInput()->withErrors($errors);
        }

        // Check if application exists and owned by requester
        $application = $appRepo->getApplicationByTrackingCode($request->input('trackingcode'));
        if( ! $application ) {            
            return back()->withInput()->withErrors(['اطلاعات متقاضی پیدا نشد. شماره پیگیری را کنترل کنید.']);
        }

        // Get payment
        $payment = $payRepo->findGtpPayedRecordByOrderNumber($request->input('orderid'));
        if( ! $payment ) {            
            return back()->withInput()->withErrors(['اطلاعات پرداخت پیدا نشد. سریال تراکنش را کنترل کنید.']);
        }

        // Convert Georgian to Jalali date
        $updated_at_jalali = Jalalian::forge(strtotime($payment->updated_at))->format("Y-m-d");
        // Convert numbers to persian characters
        $payment->updated_at = CalendarUtils::convertNumbers($updated_at_jalali);

        $data = [
            'app' => $application,
            'pay' => $payment
        ];

        return view('payment_validation.submit', $data);
    }

    // ---------------------------------------------------------------------------------------------------
}
