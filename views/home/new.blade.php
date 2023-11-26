<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/jpg" href="/assets/img/vezarat-rah.png"/>

        <title>{{ trans('app.app_title') }}</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="{{ url('/assets/plugins/bootstrap-5.1.3/css/bootstrap.rtl.min.css') }}" rel="stylesheet">

        <link href="{{ url('/assets/css/style.css') }}?time={{ time() }}" rel="stylesheet">
        <link href="{{ url('/assets/css/home.css') }}?time={{ time() }}" rel="stylesheet">
        <link href="{{ url('/assets/fonts/vazir/vazir.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/fontawesome-5.15.4/css/all.min.css') }}" rel="stylesheet">

        <script type="text/javascript" src="{{ url('/assets/plugins/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js') }}"></script>

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
                        <div class="col-6 col-sm-5 offset-sm-1">
                            <a href="https://sso.mrud.ir/login/patmak.mrud.ir/z40j1ChFRKgcV0JU"><button class="btn btn-light w-100 text-start py-2 px-1"><i class="fa fa-external-link-alt text-primary"></i> <span>ورود کاربر حقیقی</span></button></a>
                        </div>
                        <div class="col-6 col-sm-5">
                            <a href="https://sso.mrud.ir/login/patmak.mrud.ir/z40j1ChFRKgcV0JU/1"><button class="btn btn-light w-100 text-start py-2 px-1"><i class="fa fa-external-link-alt text-primary"></i> <span>ورود کاربر حقوقی</span></button></a>
                        </div>
                        <div class="col-12 col-sm-10 m-auto mb-0 mt-2 rounded-2" style="background-color: rgba(255,255,255,.5);">
                            <p class="text-danger text-center">
                                توجه : درصورتی که قصد دارید برای مجوز فعالیت دفتر مهندسی طراحی ساختمان (چند نفره) اقدام کنید لطفا از قسمت حقیقی وارد شوید!
                            </p>
                        </div>
                    </div>
                    {{-- <div class="row mt-3">
                        <div class="col-xs-12 col-lg-10 offset-lg-1">
                            <a href=""><button class="btn btn-light w-100 text-start py-2"><i class="fa fa-external-link-alt text-primary"></i> <span>درگاه ادارات کل</span></button></a>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-xs-12 col-lg-10 offset-lg-1">
                            <a href=""><button class="btn btn-light w-100 text-start py-2"><i class="fa fa-external-link-alt text-primary"></i> <span>درگاه دفتر مقررات ملی و کنترل ساختمان</span></button></a>
                        </div>
                    </div> --}}
                    <div class="row mt-3">
                        <div class="col-xs-12 col-lg-10 offset-lg-1">
                            <a href="/payment-validation"><button class="btn btn-light w-100 text-start py-2"><i class="fa fa-external-link-alt text-primary"></i> <span>درگاه صحت سنجی پرداخت ادارات کل راه و شهرسازی / نظام مهندسی/ کاردانی</span></button></a>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p class="text-center fs-6"><a target="_blank" class="text-white" href="/patmak-guide.pdf">دانلود راهنمای سامانه پاتمک</a></p>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-12">
                            <p class="text-center fs-6 text-white" >متقاضیان محترم جهت اعلام مشکلات در فرایند سامانه میتوانید با شماره 87682000-021 تماس حاصل فرمایید</p>
                        </div>
                    </div>
                </div>
            </div><!-- #login-step-1 -->
        </div><!-- .container-fluid -->

        {{ view('layouts.loader') }}     

    </body>
</html>