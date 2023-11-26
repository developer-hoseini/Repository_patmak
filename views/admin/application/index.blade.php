@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | لیست درخواست ها' )

@section('content')

    <div class="mt-5" id="app_app">
        <div class="container-fluid">
            <div class="row my-5">
                <div class="col-12">
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            نمایش <span class="length">5</span> ردیف
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" onclick="changeLength(5)"> 5 ردیف</a></li>
                            <li><a class="dropdown-item" onclick="changeLength(10)"> 10 ردیف</a></li>
                            <li><a class="dropdown-item" onclick="changeLength(25)"> 25 ردیف</a></li>
                        </ul>
                    </div>
                    <button class="btn btn-warning" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <i class="fa fa-filter"></i>
                        فیلتر ها
                    </button>
{{--                    <button class="btn btn-success" type="button" onclick="downloadexcel()">--}}
{{--                        <i class="fa fa-excel"></i>--}}
{{--                        دریافت excel--}}
{{--                    </button>--}}
                </div>
                <div class="col-12" id="filters">
                    <div class="collapse" id="collapseExample">
                        <div class="row">
{{--                            <div class="col-2 py-2">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="application_id">شناسه</label>--}}
{{--                                    <input type="text" id="application_id" class="form-control"--}}
{{--                                           onchange="changeCondition(this,'application_id')"--}}
{{--                                           placeholder="شناسه را وارد کنید.">--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="person_type_title">نوع</label>
                                    <select name="" id="person_type_title" class="form-control" onchange="changeCondition(this,'person_type_title')">
                                        <option value="">نوع کاربر را مشخص کنید.</option>
                                        <option value="حقیقی">حقیقی</option>
                                        <option value="حقوقی">حقوقی</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="p_name">نام</label>
                                    <input type="text" id="p_name" class="form-control"
                                           onchange="changeCondition(this,'p_name')"
                                           placeholder="نام را وارد کنید.">
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="p_lname">نام خانوادگی</label>
                                    <input type="text" id="p_lname" class="form-control"
                                           onchange="changeCondition(this,'p_lname')"
                                           placeholder="نام خانوادگی را وارد کنید.">
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="p_ncode">کدملی</label>
                                    <input type="text" dir="ltr" id="p_ncode" class="form-control"
                                           onchange="changeCondition(this,'p_ncode')"
                                           placeholder="کدملی را وارد کنید.">
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="mobile">شماره تماس</label>
                                    <input type="text" dir="ltr" id="mobile" class="form-control"
                                           onchange="changeCondition(this,'mobile')"
                                           placeholder="شماره تماس را وارد کنید.">
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="province_title">استان</label>
                                    <select name="" id="province_title" class="form-control" onchange="changeCondition(this,'province_title')">
                                        <option value="">استان را انتخاب کنید.</option>
                                        <option value="آذربایجان شرقی">آذربایجان شرقی</option>
                                        <option value="آذربایجان غربی">آذربایجان غربی</option>
                                        <option value="اردبیل">اردبیل</option>
                                        <option value="اصفهان">اصفهان</option>
                                        <option value="البرز">البرز</option>
                                        <option value="ایلام">ایلام</option>
                                        <option value="بوشهر">بوشهر</option>
                                        <option value="تهران">تهران</option>
                                        <option value="خراسان جنوبی">خراسان جنوبی</option>
                                        <option value="خراسان رضوی">خراسان رضوی</option>
                                        <option value="خراسان شمالی">خراسان شمالی</option>
                                        <option value="خوزستان">خوزستان</option>
                                        <option value="زنجان">زنجان</option>
                                        <option value="سمنان">سمنان</option>
                                        <option value="سیستان و بلوچستان">سیستان و بلوچستان</option>
                                        <option value="فارس">فارس</option>
                                        <option value="قزوین">قزوین</option>
                                        <option value="قم">قم</option>
                                        <option value="لرستان">لرستان</option>
                                        <option value="مازندران">مازندران</option>
                                        <option value="مرکزی">مرکزی</option>
                                        <option value="هرمزگان">هرمزگان</option>
                                        <option value="همدان">همدان</option>
                                        <option value="چهارمحال و بختیاری">چهارمحال و بختیاری</option>
                                        <option value="کردستان">کردستان</option>
                                        <option value="کرمان">کرمان</option>
                                        <option value="کرمانشاه">کرمانشاه</option>
                                        <option value="کهگیلویه و بویراحمد">کهگیلویه و بویراحمد</option>
                                        <option value="گلستان">گلستان</option>
                                        <option value="گيلان">گيلان</option>
                                        <option value="یزد">یزد</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="status_title">وضعیت درخواست</label>
                                    <select name="" id="status_title" class="form-control" onchange="changeCondition(this,'status_title')">
                                        <option value="">وضعیت درخواست را مشخص کنید.</option>
                                        <option value="درخواست در حال ثبت">درخواست در حال ثبت</option>
                                        <option value="پرداخت شده">پرداخت شده</option>
                                        <option value="پرداخت نشده">پرداخت نشده</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-2 py-2">
                                <div class="form-group">
                                    <label for="tracking_code">کد رهگیری</label>
                                    <input type="text" dir="ltr" id="tracking_code" class="form-control"
                                           onchange="changeCondition(this,'tracking_code')"
                                           placeholder="کد رهگیری را وارد کنید.">
                                </div>
                            </div>
                            <div class="col-12 py-2">
                                <button class="btn btn-success" onclick="setFilters()">اعمال فیلتر ها</button>
{{--
                                <button class="btn btn-secondary" onclick="resetFilters()">بازنشانی</button>
--}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 py-4" id="table" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <table id="myTable" class="table table-striped data-table">

                            </table>
                        </div>
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">

                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="spinner">
                    <div class="spinner-border m-auto" role="status" style="width: 5rem; height: 5rem;display: block;">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-down')
    @parent
    <script type="text/javascript">

        let conditions = {
            length: 5,
            start: 0,
            page: 1,
            draw: 1,
        };

        var table = null;

        function ajax_data(data = [], method = 'GET', url = '') {
            var tmp = null;
            $.ajax({
                async: false,
                global: false,
                dataType: 'json',
                url: url,
                method: method,
                data: data,
                success: function (response) {
                    tmp = response;
                }
            });
            return tmp;
        }

        function makeTable() {
            $('#spinner').show();
            $('#table').hide();

            if (table != null) {
                table.destroy();
            }

            setTimeout(() => {
                let data = ajax_data(conditions, 'POST', '/admin/application');

                $('#spinner').hide();
                $('#table').show();

                table = $('#myTable').DataTable({
                    data: data.data,
                    columns: data.columns,
                    searching: false,
                    ordering: false,
                    paging: false,
                });

                $('.pagination').empty();
                let page = 1;
                if (conditions.start != 0) {
                    page = (conditions.start / conditions.length) + 1;
                }
                conditions.page = page;


                if (conditions.page > 3) {
                    $('.pagination').append('<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="changePagination(this,1)">1</a></li>');
                    $('.pagination').append('<li class="page-item disabled"><a class="page-link" style="cursor: pointer;">...</a></li>');
                }

                if (conditions.page != 1) {
                    $('.pagination').append('<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="changePagination(this,' + (conditions.page - 1) + ')">' + (conditions.page - 1) + '</a></li>');
                }
                $('.pagination').append('<li class="page-item active"><a class="page-link" style="cursor: pointer;">' + page + '</a></li>');

                if (conditions.page != (Math.floor(data.count / conditions.length))) {
                    $('.pagination').append('<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="changePagination(this,' + (conditions.page + 1) + ')">' + (conditions.page + 1) + '</a></li>');
                }

                if (conditions.page < (Math.floor(data.count / conditions.length) - 1)) {
                    $('.pagination').append('<li class="page-item disabled"><a class="page-link" style="cursor: pointer;">...</a></li>');
                    $('.pagination').append('<li class="page-item"><a class="page-link" style="cursor: pointer;" onclick="changePagination(this,' + (Math.floor(data.count / conditions.length)) + ')">' + (Math.floor(data.count / conditions.length)) + '</a></li>');
                }

            }, 500)
        }

        function changeLength(length) {
            conditions.length = length;
            conditions.start = 0;
            $('.length').text(length);

            makeTable();
        }

        function changePagination(item, number) {
            conditions.start = conditions.length * (number - 1);
            conditions.page = number;

            $('.page-item').removeClass('active');
            $(item).addClass('active');

            makeTable();
        }

        function changeCondition(item, key) {
            let status = true;

            if(key == 'p_name') {
                if($(item).val() < 3) {
                    console.log(1)
                    status = false;
                }
            }

            if(key == 'p_lname') {
                if($(item).val() < 3) {
                    status = false;
                }
            }

            if(status) {
                if ($(item).val() != '') {
                    eval('conditions.' + key + '= $(item).val()');
                } else {
                    if (eval('typeof conditions.' + key + ' !== "undefined"')) {
                        eval('delete conditions.' + key);
                    }
                }
            }else {
                if (eval('typeof conditions.' + key + ' !== "undefined"')) {
                    eval('delete conditions.' + key);
                }
            }
        }

        function setFilters() {
            conditions.start = 0;

            makeTable();
        }

        function resetFilters() {
            if (Object.keys(conditions).length > 3) {
                let i = {};
                i.start = conditions.start;
                i.length = conditions.length;
                i.draw = conditions.draw;

                conditions = i;

                $('#filters input').each(function (el) {
                    $($('#filters input')[el]).val('')
                });

                $('#filters option').each(function (el) {
                    $($('#filters option')[el]).removeAttr('selected')
                })

                $('#filters option[value=""]').each(function (el) {
                    $($('#filters option[value=""]')[el]).attr('selected','selected')
                })

                makeTable();
            }
        }

        function serialize(obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        }

        function downloadexcel() {
            const a = document.createElement("a");
            a.setAttribute('href','/admin/export?'+ serialize(conditions));
            a.setAttribute('target','_blank');
            a.setAttribute('id','export_excel');
            a.innerHTML = 'export';
            document.getElementById("app_app").appendChild(a);
            a.click();
            document.getElementById("export_excel").remove();
            console.log(a)
        }

        makeTable();
    </script>
    {{--    <script type="text/javascript" src="{{ url('/assets/js/admin/dt.js') }}?time={{ time() }}"></script>--}}
@endsection