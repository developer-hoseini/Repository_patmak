@extends('layouts.app')

@section('title', 'پرداخت')

@section('content')
    <div class="mt-5">
        {{ view('application.steps') }}
    </div>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <h5 class="text-right">نمایش کد رهگیری درخواست:</h5>
            </div>
        </div>
        <div class="row mt-3 mb-3">
            <div class="col-12">
                <p class="text-center text-success"><i class="fa fa-check-circle fa-5x"></i></p>
                <h4 class="text-center">درخواست با موفقیت ثبت شد.</h4>
                <p class="text-center fs-4 my-3"><u>نتیجه بررسی اطلاعات شما توسط اداره کل استان توسط پیامک اعلام خواهد
                        شد.</u></p>
                <p class="text-center">کد پیگیری: <strong>{{ $tracking_code }}</strong></p>
                <p class="text-center">شماره سفارش (سریال): <strong>{{ $serial }}</strong></p>
                <p class="text-center">جهت دریافت رسید ارائه خدمت از دکمه زیر استفاده کنید.</p>
                <p class="text-center">ارائه این رسید به سازمان نظام مهندسی ساختمان استان اجباری می باشد.</p>
            </div><!-- ./col-12 -->
        </div><!-- ./row -->
        <div class="row">
            <div class="col-4 offset-4 text-center">
                <a href="/application/{{ $application_id }}/receipt" target="_blank">
                    <button class="btn btn-default border border-dark btn-form"><b>دریافت رسید ارائه خدمت</b></button>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('js-down')
    @parent

    <script>
        $(document).ready(function () {
            activeIconStep5();
        });
    </script>
@endsection