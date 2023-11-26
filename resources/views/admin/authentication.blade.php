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
        <script type="text/javascript" src="{{ url('/assets/js/admin/login.js') }}?time={{ time() }}"></script>

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
                            <img class="login-vlogo" src="{{ url('/assets/img/inbr.png') }}" alt="لوگو سازمان مقررات ملی ساختمان">
                        </div>
                    </div>
                    <div class="row mt-5"></div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <h1 class="text-center">{{ trans('app.app_title_summerized') }}</h1>
                            <h1 class="text-center">ورود مدیران و کارشناسان</h1>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-10 offset-1">
                            <form id="login-form" action="/admin/authentication">                            
                                <div class="input-group">
                                    <input type="text" name="username" class="form-control login-input-style" placeholder="نام کاربری" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control login-input-style" placeholder="کلمه عبور" required />
                                    <span class="input-group-text text-white" id="basic-addon1"><i class="fa fa-mobile-alt"></i></span>
                                </div> 
{{--                                <div class="input-group">--}}
{{--                                    <input type="text" name="captcha" class="form-control login-input-style" placeholder="نتیجه عملیات ریاضی داخل کادر"  />--}}
{{--                                    <span class="input-group-text text-white" id="basic-addon1"><img src="{{ captcha_src() }}" id="captcha-img" class="captcha"></img><i id="reload-captcha" class="fa fa-redo fa-flip-horizontal"></i></span>--}}
{{--                                </div>--}}
                                <div class="mt-5 text-center">
                                    <button type="submit" class="btn btn-success">ورود</button>
                                </div>                                                            
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- #login-step-1 -->
        </div><!-- .container-fluid -->
        {{ view('layouts.loader') }}     
    </body>
</html>