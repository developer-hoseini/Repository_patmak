@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | گزارشگیری' )

@section('content')
    <div class="mt-3" id="app_app">
        <div class="container-fluid">
            <form action="{{ route('admin.report.big.store') }}" method="post">
                <div class="row min-vh-100 d-flex align-items-center justify-content-center">
                    <div class="col-sm-10">
                        <div class="row my-5">
                            <div class="col-sm-8">
                                <h4 class="fw-bold">گزارشگیری کلان</h4>
                            </div>
                            <div class="col-4 py-2">
                                <div class="card border-success" style="border-radius: 10px;">
                                    <div class="card-body p-3">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-4 d-flex align-items-center justify-content-center">
                                                    بازه زمانی را انتخاب کنید.
                                                </div>
                                                <div class="col-4">
                                                    <label for="start_date">از تاریخ</label>
                                                    <input type="text" id="start_date" class="form-control" name="start_date">
                                                </div>
                                                <div class="col-4">
                                                    <label for="end_date">تا تاریخ</label>
                                                    <input type="text" id="end_date" class="form-control" name="end_date">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-sm-3 py-3">
                                <select name="province_id" id="province_id"
                                        class="form-control form-control-lg">
                                    <option value="" selected>استان</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-sm-3 py-3">
                                <select name="payment_status_id" id="payment_status_id"
                                        class="form-control form-control-lg">
                                    <option value="" selected>وضعیت پرداخت</option>
                                    @foreach($payment_statuses as $payment_status)
                                        <option value="{{ $payment_status->status_id }}">{{ $payment_status->status_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-sm-3 py-3">
                                <select name="person_type_id" id="person_type_id"
                                        class="form-control form-control-lg">
                                    <option value="" selected>نوع شخص</option>
                                    @foreach($pts as $pt)
                                        <option value="{{ $pt->person_type_id }}">{{ $pt->person_type_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row my-5">
                            <div class="col-sm-4 m-auto">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button class="btn btn-success d-block w-100">
                                            <i class="fa fa-refresh"></i>
                                            تازه سازی
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn btn-success d-block w-100">
                                            <i class="fa fa-cloud-download"></i>
                                            گزارش گیری
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

