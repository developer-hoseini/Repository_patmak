@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | چک کردن دوباره وضعیت پرداخت' )

@section('content')

<div class="mt-5">
    <div class="container-fluid">
        @if (session('message'))
        <div class="row my-5">
            <div class="col-12">                
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>               
            </div>
        </div>
        @endif
        <div class="row my-5">
            <div class="col-12">
                
                <table id="myTable" class="table table-striped data-table">
                    <tr>
                        <th>شماره سفارش</th>
                        <th>شماره تراکنش</th>
                        <th>مبلغ</th>
                        <th>زمان</th>
                        <th>وضعیت</th>
                        <th>شماره مرجع</th>
                        <th>شماره پیگیری</th>
                        <th>پیام</th>
                        <th>عملیات</th>
                    </tr>
                    
                    @if (count($transactions))
                        @foreach ($transactions as $item)
                        <tr>
                            <td>{{ $item->order_number }}</td>
                            <td>{{ $item->transaction_id }}</td>
                            <td>{{ $item->amount }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td>{{ $item->status_id }}</td>
                            <td>{{ $item->rrn }}</td>
                            <td>{{ $item->trace }}</td>
                            <td>{{ $item->message }}</td>
                            @if (strlen($item->transaction_id))
                                <td><a target="_self" href="/admin/transaction-check/{{ $item->transaction_id}}/{{$item->order_number}}"><button class="btn btn-success">استعلام</button></a></td>                                
                            @else
                                <td></td>
                            @endif
                        </tr>
                        @endforeach
            
                    @else
                    <tr>
                        <td colspan="8">رکوردی موجود نیست.</td>
                    </tr>
                    @endif
                        
                    
                </table>              
            </div>
        </div>        
    </div>
</div>    

@endsection
