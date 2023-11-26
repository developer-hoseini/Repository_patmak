@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | لیست درخواست ها' )

@section('content')
    <div class="mt-3" id="app_app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12" id="filters">
                    <div class="row">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-2 py-2">
                                    <div class="form-group">
                                        <select name="" id="province_title" class="form-control"
                                                onchange="changeCondition(this,'province_title')">
                                            <option value="">استان</option>
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
                                        <select name="" id="admin_type_id" class="form-control"
                                                onchange="changeCondition(this,'admin_type_id')">
                                            <option value="">گروه کاربری</option>
                                            @foreach($admin_types as $admin_type)
                                                <option value="{{ $admin_type->type_id }}">{{ $admin_type->type_title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-2 py-2">
                                    <div class="form-group">
                                        <select name="" id="is_active" class="form-control"
                                                onchange="changeCondition(this,'is_active')">
                                            <option value="">وضعیت کاربر</option>
                                            <option value="0">غیرفعال</option>
                                            <option value="1">فعال</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2 d-flex align-items-center justify-content-end py-2">
                            <button class="btn btn-success mx-1" onclick="setFilters()">اعمال فیلتر ها</button>
                            {{--
                                                        <button class="btn btn-secondary mx-1" onclick="resetFilters()">بازنشانی</button>
                            --}}
                        </div>
                    </div>
                </div>
                <div class="col-12 py-5" id="table" style="display: none;">
                    <div class="row">
                        <div class="col-12 d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h4 class="fw-bold">لیست کاربران</h4>
                            </div>
                            <div>
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
                                <a href="{{ route('admin.admin.create') }}" class="btn btn-success mx-1">
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </div>
                        </div>
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
                let data = ajax_data(conditions, 'POST', '/admin/admin');

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

            if (key == 'p_name') {
                if ($(item).val() < 3) {
                    console.log(1)
                    status = false;
                }
            }

            if (key == 'p_lname') {
                if ($(item).val() < 3) {
                    status = false;
                }
            }

            if (status) {
                if ($(item).val() != '') {
                    eval('conditions.' + key + '= $(item).val()');
                } else {
                    if (eval('typeof conditions.' + key + ' !== "undefined"')) {
                        eval('delete conditions.' + key);
                    }
                }
            } else {
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
                    $($('#filters option[value=""]')[el]).attr('selected', 'selected')
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
            a.setAttribute('href', '/admin/export?' + serialize(conditions));
            a.setAttribute('target', '_blank');
            a.setAttribute('id', 'export_excel');
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