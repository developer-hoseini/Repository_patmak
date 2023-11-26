@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | گزارشگیری' )

@section('content')
    <div class="mt-3" id="app_app">
        <div class="container-fluid">
            <div class="row min-vh-100 d-flex align-items-center justify-content-center">
                <div class="col-sm-10" style="margin-top: -100px;">
                    <div class="row my-5">
                        <div class="col-sm-12">
                            <h4 class="fw-bold">گزارشگیری</h4>
                        </div>
                    </div>
                    <div class="row my-5">
                        <div class="col-sm-3 py-3">
                            <select name="license_type_id" id="license_type_id" class="form-control form-control-lg">
                                <option value="" selected>نوع پروانه</option>
                                @foreach($license_types as $license_type)
                                    <option value="{{ $license_type->license_type_id }}">{{ $license_type->license_type_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="study_field_id" id="study_field_id" class="form-control form-control-lg">
                                <option value="" selected>عنوان رشته مدرک تحصیلی</option>
                                @foreach($study_fields as $study_field)
                                    <option value="{{ $study_field->study_field_id }}">{{ $study_field->study_field_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="mrud_org_id" id="mrud_org_id" class="form-control form-control-lg">
                                <option value="" selected>نام سازمان</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization->morg_id }}">{{ $organization->morg_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="province_id" id="province_id" class="form-control form-control-lg">
                                <option value="" selected>استان</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row my-5">
                        <div class="col-sm-3 py-3">
                            <select name="request_type_id" id="request_type_id" class="form-control form-control-lg">
                                <option value="" selected>نوع درخواست</option>
                                @foreach($request_types as $request_type)
                                    <option value="{{ $request_type->req_type_id }}">{{ $request_type->req_type_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="request_province_id" id="request_province_id" class="form-control form-control-lg">
                                <option value="" selected>استان محل اشتغال</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="payment_status_id" id="payment_status_id" class="form-control form-control-lg">
                                <option value="" selected>وضعیت پرداخت</option>
                                @foreach($payment_statuses as $payment_status)
                                    <option value="{{ $payment_status->status_id }}">{{ $payment_status->status_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="user_province_id" id="user_province_id" class="form-control form-control-lg">
                                <option value="" selected>استان محل عضویت</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row my-5">
                        <div class="col-sm-3 py-3">
                            <input type="text" name="ncode" id="ncode" class="form-control form-control-lg" placeholder="کدملی">
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="request_province_id" id="request_province_id" class="form-control form-control-lg">
                                <option value="" selected>عضویت در</option>
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <select name="person_type_id" id="person_type_id" class="form-control form-control-lg">
                                <option value="" selected>نوع شخص</option>
                                @foreach($person_types as $person_type)
                                    <option value="{{ $person_type->person_type_id }}">{{ $person_type->person_type_title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3 py-3">
                            <input type="text" name="rrn" id="rrn" class="form-control form-control-lg" placeholder="کد رهگیری">
                        </div>
                    </div>
                    <div class="row my-5">
                        <div class="col-sm-3 py-3">
                            <input type="text" name="fname" id="fname" class="form-control form-control-lg" placeholder="نام">
                        </div>
                        <div class="col-sm-3 py-3">
                            <input type="text" name="lname" id="lname" class="form-control form-control-lg" placeholder="نام خانوادگی">
                        </div>
                        <div class="col-sm-3 py-3">
                            <input type="text" name="mobile" id="mobile" class="form-control form-control-lg" placeholder="شماره موبایل">
                        </div>
                        <div class="col-sm-3 py-3">
                            <input type="text" name="created_date" id="created_date" class="form-control form-control-lg"
                                   placeholder="تاریخ ثبت">
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
        </div>
    </div>
@endsection

