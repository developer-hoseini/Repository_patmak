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
            <link href="{{ url('/assets/plugins/persian-datepicker/persian-datepicker.min.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.style.css') }}?time={{ time() }}" rel="stylesheet" type="text/css"/>
        @show

        @section('js-top')
            <script type="text/javascript" src="{{ url('/assets/plugins/jquery-3.6.0/jquery-3.6.0.min.js') }}"></script>
            <script type="text/javascript" src="{{ url('/assets/plugins/bootstrap-5.1.3/js/bootstrap.min.js') }}"></script>
        @show

        <script type="text/javascript">
            var siteurl = "{{ route('index') }}";
        </script>

        <style type="text/css">
            .alarm {
                animation: color-change 1s infinite;
            }

            @keyframes color-change {
                0% { color: red; }
                50% { color: rgba(255,255,255,.55); }
                100% { color: red; }
            }

            .dropdown-menu[data-bs-popper] {
                right: inherit;
                left: 0;
            }
        </style>
    </head>
    <body>
        {{ view('layouts.header') }}  
        {{ view('layouts.navbar') }}  

        @yield('content')

        {{ view('layouts.footer') }}  

        @section('js-down')
        <script type="text/javascript" src="{{ url('/assets/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/persian-datepicker/persian-date.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/persian-datepicker/persian-datepicker.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/plugins/Toast-Popup-Plugin-jQuery-Toaster/toast.script.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/script.js') }}?time={{ time() }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/application/application.js') }}?time={{ time() }}"></script>
        @show

        {{ view('layouts.loader') }}

        <script type="text/javascript">
            function ajax_data(data =[],method='GET',url='')
            {
                var tmp = null;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    async: false,
                    global: false,
                    dataType: 'json',
                    url:url,
                    method:method,
                    data:data,
                    success:function(response)
                    {
                        tmp = response;
                    }
                });
                return tmp;
            }

            let data = ajax_data({},'GET','/application/get_success');
            data.forEach(function (notif) {

                if(notif.read == 0) {
                    $('#dropdownMenuButton1').addClass('alarm');
                }

                $('#notifDropdown').append('<li><a class="dropdown-item" href="/application/'+ notif.application_id +'/receipt">رسید درخواست با شناسه '+ notif.application_id +' صادر گردید برای مشاهده کلیک کنید.</a></li>')
            })
        </script>

    </body>
</html>
