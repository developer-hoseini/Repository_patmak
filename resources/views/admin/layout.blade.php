<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/jpg" href="/assets/img/vezarat-rah.png"/>
    <base href="{{ url('/') }}" target="_blank">

    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('css')
        <link href="{{ url('/assets/plugins/bootstrap-5.1.3/css/bootstrap.rtl.min.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/css/style.css') }}?time={{ date("Ymd") }}" rel="stylesheet">
        <link href="{{ url('/assets/css/application.css') }}?time={{ date("Ymd") }}" rel="stylesheet">
        <link href="{{ url('/assets/fonts/vazir/vazir.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/fontawesome-5.15.4/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
        <link href="{{ url('/assets/plugins/persian-datepicker/persian-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
        {{--
                    <link href="{{ url('/assets/plugins/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
        --}}

        <link href="{{ url('/assets/plugins/DataTables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.style.css') }}" rel="stylesheet" type="text/css"/>
    @show

    @section('js-top')
        <script type="text/javascript" src="{{ url('/assets/plugins/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/popper-2.4.4/popper.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js') }}"></script>
    @show



</head>
<body>
{{ view('layouts.header') }}
{{ view('admin.navbar') }}

@yield('content')

{{ view('layouts.footer') }}

@section('js-down')
    <script type="text/javascript" src="{{ url('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/plugins/persian-datepicker/persian-date.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/plugins/persian-datepicker/persian-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.script.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    {{--
            <script type="text/javascript" src="{{ url('/assets/plugins/DataTables/datatables.min.js') }}"></script>
    --}}
    <script type="text/javascript" src="{{ url('/assets/js/custom-data-table.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/script.js') }}?time={{ date("Ymd") }}"></script>
@show

{{ view('layouts.loader') }}

</body>
</html>
