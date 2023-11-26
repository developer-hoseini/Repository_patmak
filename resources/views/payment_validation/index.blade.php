@extends('layouts.payment_validation')

@section('title', trans('app.page_title') . ' | صحت سنجی پرداخت')

@section('content')

<div class="container my-5">
    <div class="row py-5">
        <div class="col-12 text-center">
            <h4>صحت سنجی پرداخت</h4>    
        </div>          
    </div>
    @if ($errors->any())
        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>            
    @endif
    <div class="row mt-5">
        <div>
            <div class="col-xs-10 offset-xs-1 col-md-4 offset-md-4 col-xl-2 offset-xl-5">
                <form action="/payment-validation" method="POST">
                    <div class="form-group">
                        <label for="trackingcode">کد پیگیری متقاضی <i class="fa fa-star text-required"></i></label> 
                        <input id="trackingcode" name="trackingcode" value="{{ old('trackingcode') }}" type="text" class="form-control" >
                    </div> 
                    <div class="form-group mt-4">
                        <label for="orderid">سریال تراکنش <i class="fa fa-star text-required"></i></label> 
                        <input id="orderid" name="orderid" type="text" value="{{ old('orderid') }}" class="form-control" >
                    </div>
                    <div class="form-group mt-4">
                        <label for="captcha" class="w-100 text-center"><img src="{{ captcha_src() }}" alt="captcha"></label> 
                        <input id="captcha" name="captcha" type="text" class="form-control mt-2" placeholder="نتیجه عملیات ریاضی فوق" >
                    </div>  
                    @csrf
                    <div class="form-group mt-5">
                        <button type="submit" class="btn btn-success w-100">تایید</button>
                    </div>
                </form>                    
            </div>
        </div>
    </div>
</div>

@endsection