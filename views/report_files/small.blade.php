<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * {
            font-family:iransans;
        }

        body {
            background-color: #FFF;
            font-family: iransans, sans-serif;
        }

        h1,h2,h3,h4,h5,h6,p {
            margin: 0;
        }

    </style>
</head>
<body>

<div style="width: 100%;display: flex;align-items: center">
    <div style="width: 20%;">
        <img src="https://patmak.mrud.ir/assets/pdf/rah.png" style="margin: auto;display: block;" alt="">
    </div>
    <div style="width: 60%;">
        <h1 style="text-align: center;">گزارش آماری سامانه پاتمک</h1>
        <p style="text-align: center;">
            <span>ار تاریخ: </span>
            <span>تا تاریخ: </span>
        </p>
    </div>
    <div style="width: 20%;">
        <img src="https://patmak.mrud.ir/assets/pdf/image.png" style="margin: auto;display: block;"  alt="">
    </div>
</div>

<div style="width: 100%;">
    <table style="border-collapse: collapse;width: 100%;">
        <thead>
        <tr>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">ردیف</th>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">استان</th>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">نوع درخواست</th>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">تعداد پرداخت شده</th>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">تعداد پرداخت نشده</th>
            <th style="border:1px solid #DDD;text-align: center;padding: 8px;background-color: #DDD;">مبلغ کل</th>
        </tr>
        </thead>
        <tbody>
           @foreach($items as $index => $item)
               <tr>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ $index + 1 }}</td>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ $item->province_title }}</td>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ $item->req_type_title }}</td>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ $item->success }}</td>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ $item->failed }}</td>
                   <td style="border:1px solid #DDD;text-align: center;padding: 8px;">{{ number_format($item->total) }}</td>
               </tr>
           @endforeach
        </tbody>
    </table>
</div>

</body>
</html>

