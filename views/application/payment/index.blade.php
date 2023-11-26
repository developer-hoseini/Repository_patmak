@extends('layouts.app')

@section('title', 'پرداخت')

@section('content')

<div class="mt-5">
    {{ view('application.steps') }}
</div>
<div class="container">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">پرداخت:</h5>
        </div>
    </div>
    <div class="row mt-3 mb-5">
        <div class="col-12">
            <table class="table table-striped cell-border table-bordered w-100" >
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>وضعیت</th>
                        <th>مبلغ</th>
                        <th>عملیات</th>                        
                    </tr>
                </thead>                
                <tbody>
                    @php $amount_sum = 0; $payed_records = 0; $all_payments_done = true; @endphp

                    @foreach ($payments as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->amount }} ریال</td>
                        <td>
                            <form class="form-request-pay" action="{{ $action_url }}">
                                <input type="hidden" name="khazaneh" value="{{ $item->is_khazaneh }}">
                                <button class="btn btn-success" @if ($item->is_payed) disabled @endif>پرداخت</button>
                            </form>
                        </td>
                    </tr>
                    @php $amount_sum += (int) $item->amount; @endphp
                    @php $all_payments_done = $all_payments_done && $item->is_payed; @endphp

                    @endforeach   
                    <tr>
                        <td>جمع کل</td>
                        <td class="text-center fw-bold" colspan="3">{{ $amount_sum }} ریال</td>
                    </tr>                     
                </tbody>               
            </table>     
        </div><!-- ./col-12 -->         
    </div><!-- ./row -->
    <div class="row mt-5">
        <div class="col-4 offset-4">
            <form id="payments-step-form" action="">
                @php $all_payments_done = intval($all_payments_done); @endphp
                <input type="hidden" value="{{ $all_payments_done }}" name="all_payments_done">
                <input type="hidden" value="{{ $application_id }}" name="application_id">
                <button type="submit" class="btn btn-confirm" @if( ! $all_payments_done ) disabled  @endif>مرحله بعد</button>
            </form>
        </div>
    </div>

</div>

<form id="bank-forward-form" action="https://pec.shaparak.ir/NewPG/pay" method="POST">
    <input type="hidden" name="transaction_id" id="transaction_id" value="">
    <input type="hidden" name="sign" id="sign" value="">
</form>


@endsection

@section('js-down')
   @parent
    <script type="text/javascript" src="{{ url('/assets/js/application/payment.js') }}?time={{ time() }}"></script>

@endsection