@extends('layouts.payment_validation')

@section('title', trans('app.page_title') . ' | صحت سنجی پرداخت')

@section('content')

<div class="container my-5">
    <div class="row pt-5">
        <div class="col-12 text-center">
            <h4>صحت سنجی پرداخت</h4>    
        </div>          
    </div>
    <div class="row mt-5">
        <div class="col-12"><h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات متقاضی</h5></div>                        
    </div>
    <div class="row mt-4">
        <div class="col-10 offset-1">
            
            <div class="row">
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="nationalcode">کد ملی / شناسه ملی:</i></label> 
                        <input id="nationalcode" name="nationalcode" type="text" class="form-control" disabled value="{{ ($app->person_type_id === 1) ? $app->p_ncode : $app->org_ncode }}" >
                    </div>                         
                </div> 
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="name">نام و نام خانوادگی / نام شرکت:</i></label> 
                        <input id="name" name="name" type="text" class="form-control" disabled value="{{ ($app->person_type_id === 1) ? $app->fullname : $app->org_name }}">
                    </div>                         
                </div>
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="org">سازمان:</i></label> 
                        <input id="org" name="org" type="text" class="form-control" disabled value="{{ $app->morg_title }}" >
                    </div>                         
                </div>
            </div>                                        
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12"><h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات پرداخت</h5></div>                        
    </div>
    <div class="row mt-4">
        <div class="col-10 offset-1">                
            <div class="row">
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="paydate">تاریخ پرداخت:</i></label> 
                        <input id="paydate" name="paydate" type="text" class="form-control" disabled value="{{ $pay->updated_at }}" >
                    </div>                         
                </div> 
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="payamount">مبلغ پرداخت:</i></label> 
                        <input id="payamount" name="payamount" type="text" class="form-control" disabled value="{{ $pay->amount }} ریال" >
                    </div>                         
                </div>
                <div class="col-xs-12 col-md-4 mt-3">
                    <div class="form-group">
                        <label for="payserial">سریال تراکنش:</i></label> 
                        <input id="payserial" name="payserial" type="text" class="form-control" disabled value="{{ $pay->order_number }}">
                    </div>                         
                </div>
            </div>                                        
        </div>
    </div>

    <div class="row mt-5 pt-5">
        <div class="col-10 offset-1 text-center">                
            <a class="btn btn-light  btn-outline-dark px-5 py-2" href="">بازگشت به صفحه قبل</a>                     
        </div>
    </div>

</div>


    
@endsection