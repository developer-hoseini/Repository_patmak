<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/jpg" href="/assets/img/vezarat-rah.png"/>

        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @section('css')
            <link href="{{ url('/assets/plugins/bootstrap-5.1.3/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
            <link href="{{ url('/assets/css/style.css') }}?time={{ time() }}" rel="stylesheet">
            <link href="{{ url('/assets/css/application.css') }}?time={{ time() }}" rel="stylesheet">
            <link href="{{ url('/assets/fonts/vazir/vazir.css') }}" rel="stylesheet">
            <link href="{{ url('/assets/plugins/fontawesome-5.15.4/css/all.min.css') }}" rel="stylesheet">
            <link href="{{ url('/assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
            <link href="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.style.css') }}?time={{ time() }}" rel="stylesheet" type="text/css"/>

        @show

        @section('js-top')
            <script type="text/javascript" src="{{ url('/assets/plugins/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
            <script type="text/javascript" src="{{ url('/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js') }}"></script>
        @show

        

    </head>
    <body>
        {{ view('layouts.header') }}  

        @yield('content')

        {{ view('layouts.footer') }}  

        @section('js-down')
        <script type="text/javascript" src="{{ url('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.script.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/script.js') }}?time={{ time() }}"></script>
        @show

        {{ view('layouts.loader') }}        

    </body>
</html>
