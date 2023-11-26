@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | گزارش' )

@section('content')

    <div class="mt-5">
        <div class="container-fluid">
            {{--
                    <div class="row my-5">
                        <div class="col-12">
                            <table class="table table-striped">
                                <tr>
                                    <td><strong>تعداد کل پرداخت ها:</strong></td>
                                    <td>{{ $person_type['payments_total'] }}</td>
                                    <td><strong>تعداد پرداخت ها حقیقی:</strong></td>
                                    <td>{{ $person_type['payments_regular'] }}</td>
                                    <td><strong>تعداد پرداخت ها حقوقی:</strong></td>
                                    <td>{{ $person_type['payments_legal'] }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
            --}}
            <!-- Pie Charts -->
            <div class="row mt-3">
                <div class="col-md-5"><h4>نوع متقاضی</h4></div>
                <div class="col-md-7"><h4>نوع درخواست</h4></div>
            </div>
            <div class="row mt-1">
                <!-- Persons type payments -->
                <div class="col-md-2">
                    <div class="w-80">
                        <canvas id="personTypePaymnets"></canvas>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="indexes">
                        <label for=""><span>تعداد کل</span>:<strong>{{ $person_type['payments_total'] }}</strong></label>
                        <br>
                        <label for=""><span>حقیقی</span>:<strong>{{ $person_type['payments_regular'] }}</strong></label>
                        <input type="hidden" id="payment-regular" value="{{ $person_type['payments_regular'] }}"
                               data-title="حقیقی">
                        <br>
                        <label for=""><span>حقوقی</span>:<strong>{{ $person_type['payments_legal'] }}</strong></label>
                        <input type="hidden" id="payment-legal" value="{{ $person_type['payments_legal'] }}"
                               data-title="حقوقی">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="w-80">
                        <canvas id="requestTypePayments"></canvas>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="indexes">
                        @foreach($request_types as $type)
                            <label for=""><span>{{ $type->req_type_title }}</span>:<strong>{{ $type->count }}</strong></label>
                            <input type="hidden" id="req-{{ $type->req_type_id }}" value="{{ $type->count }}"
                                   data-title="{{ $type->req_type_title }}">
                            <br>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12"><h4>تعداد کل درخواست های حقیقی به تقکیک استان</h4></div>
                @foreach ($provinces_regular as $item)
                    <input type="hidden" class="regular-province-items" id="reg-province-{{ $item->province_id }}"
                           value="{{ $item->count }}" data-title="{{ $item->province_title }}">
                @endforeach
            </div>
            <div class="row mt-1">
                <div class="col-md-10 offset-md-1">
                    <div>
                        <canvas id="provincesRegular"></canvas>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-12"><h4>تعداد کل درخواست های حقوقی به تقکیک استان</h4></div>
                @foreach ($provinces_legal as $item)
                    <input type="hidden" class="legal-province-items" id="reg-province-{{ $item->province_id }}"
                           value="{{ $item->count }}" data-title="{{ $item->province_title }}">
                @endforeach
            </div>
            <div class="row mt-1 mb-5">
                <div class="col-md-10 offset-md-1">
                    <div>
                        <canvas id="provincesLegal"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <nav>
                        <div class="nav nav-tabs d-flex justify-content-center" id="nav-tab" role="tablist">
                            <button class="nav-link active d-flex flex-fill text-center h4" id="nav-big-tab" data-bs-toggle="tab" data-bs-target="#nav-big"
                                    type="button" role="tab" aria-controls="nav-big" aria-selected="true">گزارشگیری کلان
                            </button>
                            <button class="nav-link d-flex flex-fill text-center h4" id="nav-small-tab" data-bs-toggle="tab" data-bs-target="#nav-small"
                                    type="button" role="tab" aria-controls="nav-small" aria-selected="false">گزارشگیری
                                خرد
                            </button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-big" role="tabpanel"
                             aria-labelledby="nav-big-tab">
                            <form action="{{ route('admin.report.big.store') }}" method="post">
                                <div class="row d-flex align-items-center justify-content-center">
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
                        <div class="tab-pane fade" id="nav-small" role="tabpanel" aria-labelledby="nav-small-tab">
                            <form action="{{ route('admin.report.small.store') }}" method="post">
                                <div class="row d-flex align-items-center justify-content-center">
                                    <div class="col-sm-10">
                                        <div class="row my-5">
                                            <div class="col-sm-8">
                                                <h4 class="fw-bold">گزارشگیری خرد</h4>
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
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-down')
    @parent
    <script src="{{ url('/assets/plugins/chartjs/chart.js') }}"></script>

    <!-- Person type payments initialize -->
    <script>

        var ALIZARIN = '#e74c3c';
        var PETER_RIVER = '#3498db';
        var EMERALD = '#2ecc71';
        var AMETHYST = '#9b59b6';
        var CARROT = '#e67e22';
        var SUN_FLOWER = '#f1c40f';
        var ASBESTOS = '#7f8c8d';
        var MIDNIGHT_BLUE = '#2c3e50';
        var FLAMINGO_PINK = '#f78fb3';

        // Chart 1
        var regular = $('#payment-regular');
        var legal = $('#payment-legal');
        const dataPersonType = {
            labels: [regular.data('title'), legal.data('title')],
            datasets: [
                {
                    data: [regular.val(), legal.val()],
                    backgroundColor: [ALIZARIN, PETER_RIVER],
                }
            ]
        };
        const myChart = new Chart(document.getElementById('personTypePaymnets'), {
            type: 'pie',
            data: dataPersonType,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Chart 2
        var tamdid = $('#req-1');
        var tajdid = $('#req-2');
        var ertegha = $('#req-3');
        var avalin = $('#req-4');
        var darj = $('#req-5');
        var diff_1400 = $('#req-6');
        var diff_1400_avalin = $('#req-7');
        var diff_1401 = $('#req-8');
        var diff_1401_avalin = $('#req-9');
        var dataRequestType = {
            labels: [tamdid.data('title'), tajdid.data('title'), ertegha.data('title'), avalin.data('title'), darj.data('title'), diff_1400.data('title'), diff_1400_avalin.data('title'), diff_1401.data('title'), diff_1401_avalin.data('title')],
            datasets: [
                {
                    data: [tamdid.val(), tajdid.val(), ertegha.val(), avalin.val(), darj.val(), diff_1400.val(), diff_1400_avalin.val(), diff_1401.val(), diff_1401_avalin.val()],
                    backgroundColor: [ALIZARIN, CARROT, PETER_RIVER, EMERALD, SUN_FLOWER, AMETHYST, ASBESTOS, MIDNIGHT_BLUE, FLAMINGO_PINK],
                }
            ]
        };
        const myChart2 = new Chart(document.getElementById('requestTypePayments'), {
            type: 'pie',
            data: dataRequestType,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

    </script>


    <script>

        $(document).ready(function () {

            var _data = [];
            var _lables = [];
            jQuery('.regular-province-items').each(function () {
                let currentElement = $(this);
                _lables.push(currentElement.data('title'));
                _data.push(currentElement.val());
            });

            var data = {
                labels: _lables,
                datasets: [
                    {
                        data: _data,
                        backgroundColor: [PETER_RIVER],
                    }
                ]
            };
            const myChart3 = new Chart(document.getElementById('provincesRegular'), {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

    <script>

        $(document).ready(function () {

            var _data = [];
            var _lables = [];
            jQuery('.legal-province-items').each(function () {
                let currentElement = $(this);
                _lables.push(currentElement.data('title'));
                _data.push(currentElement.val());
            });

            var data = {
                labels: _lables,
                datasets: [
                    {
                        data: _data,
                        backgroundColor: [PETER_RIVER],
                    }
                ]
            };
            const myChart3 = new Chart(document.getElementById('provincesLegal'), {
                type: 'bar',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>

    <script type="text/javascript">
        $("form").submit(function () {
            $("form").attr('target', '_self');
            return true;
        });
    </script>

@endsection