<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ trans('app.app_title') }}</title>
    <link href="{{ url('/assets/fonts/vazir/vazir.css') }}" rel="stylesheet">
    <style>
        body{font-family: Vazir; background-color: #f0f0f0;}
        .a5 {width: 148mm; height: 210mm; padding: 2mm; background-repeat: no-repeat; background-size: 100%; margin:auto; background-color: white;}
        .content{position: relative;border: 1px solid black; width: 146mm; height: 208mm;}
        .header{height: 42mm;}
        .header > div{ display: inline-block; height:30mm; float:right; margin-top:2mm;}
        .logo-vezarat{width: 22mm;}
        .title{margin-right: 37mm; font-size: 5mm; margin-top: 8mm;}
        .logo-sazman{margin-right: 38mm;width: 18mm;}
        tr{height: 10mm;}
        tr:nth-child(2n+1) {background: #ccc}
        tr > td:first-child { width: 55mm; padding-right: 6mm;}
        tr:last-child{height: auto;}
        .footer{position: absolute; width:100%; height: 15mm; bottom: 0; right: 0; padding-top:1.5mm; text-align: center; border-top: 1px solid black; font-size: 3.4mm;}
        table{border-collapse: collapse;width: 100%;}
    </style>
</head>
<body>
<div class="a5">
    <div class="content">
        <div class="header">
            <div><img class="logo-vezarat" src="/assets/img/vezarat-rah.png" alt=""></div>
            <div><h6 class="title">سامانه پاتمک</h6></div>
            <div><img class="logo-sazman" src={{ route('image',['hash' => env('IMAGE_HASH')]) }} alt=""></div>
        </div>
        <table>
            <tr>
                <td>
                    پرداخت شما  در انتظار تایید است. در صورت بروز خطا مبلغ پرداختی شما نهایتا تا مدت 72 ساعت آینده به حساب باز می گردد در غیر اینصورت با پشتیبانی تماس حاصل فرمایید.
                </td>
            </tr>
        </table>
    </div>

</div><!-- .container-fluid -->
</body>
</html>
