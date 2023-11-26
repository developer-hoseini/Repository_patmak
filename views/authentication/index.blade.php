<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/jpg" href="/assets/img/vezarat-rah.png"/>

        <title>{{ trans('app.app_title') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ url('/assets/plugins/bootstrap-5.1.3/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

        <link href="{{ url('/assets/css/style.css') }}?time={{ time() }}" rel="stylesheet">
        <link href="{{ url('/assets/css/login.css') }}?time={{ time() }}" rel="stylesheet">
        <link href="{{ url('/assets/fonts/vazir/vazir.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/fontawesome-5.15.4/css/all.min.css') }}" rel="stylesheet">

        <script type="text/javascript" src="{{ url('/assets/plugins/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>

        <script type="text/javascript" src="{{ url('/assets/js/script.js') }}?time={{ time() }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/login/login.js') }}?time={{ time() }}"></script>

    </head>
    <body>

        <div class="container-fluid">

            <div class="row" id="login-step-1">
                <div class="col-xs-12 col-md-6 offset-md-6 col-lg-6 offset-lg-6 col-xl-3 offset-xl-9  login-div">
                    <div class="row mt-3">
                        <div class="col-6 text-center">
                            <img class="login-vlogo" src="{{ url('/assets/img/vezarat-rah.png') }}" alt="لوگو وزرات راه">
                        </div>
                        <div class="col-6 text-center">
                            <img class="login-vlogo" src="{{ route('image',['hash' => env('IMAGE_HASH')]) }}" alt="لوگو سازمان مقررات ملی ساختمان">
                        </div>
                    </div>
                    <div class="row mt-5"></div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <h1 class="text-center">{{ trans('app.app_title') }}</h1>
                            <h1 class="text-center">({{ trans('app.app_title_summerized') }})</h1>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-10 offset-1">
                            <form id="login-form" action="/login-attempt">                            
                                <div class="input-group">
                                    <input type="text" name="ncode" class="form-control login-input-style" placeholder="کد ملی" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="input-group">
                                    <input type="text" name="mobile" class="form-control login-input-style" placeholder="شماره تلفن همراه" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><i class="fa fa-mobile-alt"></i></span>
                                </div> 
                                <div class="input-group">
                                    <input type="text" name="captcha" class="form-control login-input-style" placeholder="نتیجه عملیات ریاضی داخل کادر" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><img src="{{ captcha_src() }}" id="captcha-img" class="captcha"></img><i id="reload-captcha" class="fa fa-redo fa-flip-horizontal"></i></span>
                                </div>     
                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-success">درخواست رمز یکبار مصرف</button>
                                </div>                                                            
                            </form>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p class=" text-center text-white fs-6">شماره تلفن همراه بایستی متعلق به کد ملی باشد</p>
                        </div>
                    </div>
                </div>
            </div><!-- #login-step-1 -->

            <div class="row hidden" id="login-step-2">
                <div class="col-xs-12 col-md-6 offset-md-6 col-lg-6 offset-lg-6 col-xl-3 offset-xl-9 login-div">
                    <div class="row mt-3">
                        <div class="col-6 text-center">
                            <img class="login-vlogo" src="{{ url('/assets/img/vezarat-rah.png') }}" alt="لوگو وزرات راه">
                        </div>
                        <div class="col-6 text-center">
                            <img class="login-vlogo" src="{{ route('image',['hash' => env('IMAGE_HASH')]) }}" alt="لوگو سازمان مقررات ملی ساختمان">
                        </div>
                    </div>
                    <div class="row mt-5"></div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <h1 class="text-center">{{ trans('app.app_title') }}</h1>
                            <h1 class="text-center">({{ trans('app.app_title_summerized') }})</h1>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p class=" text-center text-white fs-6">رمز یکبار مصرف ارسال شده به شماره <span id="entered-mobile">09xxxxxxxxx</span> را وارد کنید.</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-10 offset-1">
                            <form id="otp-form" action="/login-verify">                            
                                <div class="input-group">
                                    <input type="text" name="otp" class="form-control login-input-style" placeholder="رمز یکبار مصرف" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><i class="fa fa-lock"></i></span>
                                </div>   
                                <div class="input-group mt-0" id="resend-otp">
                                    <input type="button" id="resend-otp-btn" class="form-control login-input-style" value="ارسال مجدد" />                                                    
                                    <span class="input-group-text text-white" id="basic-addon1"><i id="timer">{{ $otp_req_delay }}</i></span>
                                </div> 
                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-login">ورود به سامانه</button>
                                </div>   
                                <div class="mt-1 text-center">
                                    <input type="button" id="change-mobile-btn" value="تغییر شماره تلفن همراه" />   
                                </div>                                                           
                            </form>
                        </div>
                    </div>                    
                </div>
            </div><!-- login-step-2 -->


        </div><!-- .container-fluid -->

        {{ view('layouts.loader') }}     

    </body>
</html>