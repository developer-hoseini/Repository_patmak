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
                                        <select name="" id="admin_request_type_id" class="form-control"
                                                onchange="changeCondition(this,'admin_request_type_id')">
                                            <option value="">نوع عملیات</option>
                                            @foreach($admin_request_types as $admin_request_type)
                                                <option value="{{ $admin_request_type->id }}">{{ $admin_request_type->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                                <h4 class="fw-bold">لیست درخواست ها</h4>
                            </div>
                            <div>
                                <div class="dropdown d-inline-block">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                            id="dropdownMenuButton1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        نمایش <span class="length">5</span> ردیف
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li><a class="dropdown-item" onclick="changeLength(5)"> 5 ردیف</a></li>
                                        <li><a class="dropdown-item" onclick="changeLength(10)"> 10 ردیف</a></li>
                                        <li><a class="dropdown-item" onclick="changeLength(25)"> 25 ردیف</a></li>
                                    </ul>
                                </div>
                                <button class="btn btn-info" data-toggle="modal" data-target="#addRequest"
                                        onclick="addModal()">افزودن درخواست جدید
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <input type="hidden" onchange="changeCondition(this,'status')" id="requestStatus" value="0">
                            <ul class="nav nav-tabs border-0">
                                <li class="nav-item">
                                    <a class="nav-link text-dark active" target="_self" href="javascript:void(0);"
                                       onclick="changeStatus(this)" data-status="0">در انتظار تایید کارشناس</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" target="_self" href="javascript:void(0);"
                                       onclick="changeStatus(this)" data-status="1">تایید شده ها</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-dark" target="_self" href="javascript:void(0);"
                                       onclick="changeStatus(this)" data-status="2">رد شده ها</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12">
                            <div class="card border-0">
                                <div class="card-body">
                                    <table id="myTable" class="table table-striped data-table">

                                    </table>
                                </div>
                            </div>
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
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="fw-bold mb-0">مشاهده جزئیات درخواست</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-success p-2 mb-2">
                                وضعیت : <span id="modalStatus"></span>
                            </div>
                        </div>
                        <input type="hidden" id="modalId">
                        <div class="col-sm-12 mb-3" id="modalButtons">
                            <button class="btn btn-success" type="button" id="confirmRequest">تایید درخواست</button>
                            <button class="btn btn-danger" type="button" id="denyRequest">رد درخواست</button>
                        </div>
                        <div class="col-sm-12 my-2" style="display: none;" id="modalDenyBox">
                            <div class="card">
                                <div class="card-header bg-danger">
                                    <h5 class="fw-bold mb-0">علت رد درخواست</h5>
                                </div>
                                <div class="card-body">
                                    <textarea name="deny_description" class="form-control" id="modalDeny" cols="30"
                                              rows="4" placeholder="متن دلخواه را وارد کنید..."></textarea>
                                    <button class="btn btn-danger mt-3 float-start" id="denySave">تایید</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 my-2">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h5 class="fw-bold mb-0">مشخصات درخواست</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-4 my-3">
                                            نوع درخواست : <span id="modalRequestType"></span>
                                        </div>
                                        <div class="col-sm-4 my-3">
                                            نام : <span id="modalName"></span>
                                        </div>
                                        <div class="col-sm-4 my-3">
                                            موبایل : <span id="modalMobile"></span>
                                        </div>
                                        <div class="col-sm-6 my-3">
                                            گروه کاربری : <span id="modalRole"></span>
                                        </div>
                                        <div class="col-sm-6 my-3">
                                            نام خانوادگی : <span id="modalLname"></span>
                                        </div>
                                        <div class="col-sm-12 my-3">
                                            کدملی : <span id="modalP_ncode"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 my-2">
                            <div class="card">
                                <div class="card-header bg-info">
                                    <h5 class="fw-bold mb-0">مستندات درخواست</h5>
                                </div>
                                <div class="card-body">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addRequest" tabindex="-1" aria-labelledby="addRequestLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h6 class="fw-bold mb-0">ثبت درخواست جدید</h6>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.request.store') }}" target="_self" method="post"
                          enctype="multipart/form-data" id="uploadForm">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header bg-info">
                                        <h6 class="fw-bold mb-0">مشخصات درخواست</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="type_id" class="form-label">نوع عملیات</label>
                                                    <select id="type_id" name="admin_request_type_id" class="form-control">
                                                        <option value="" selected></option>
                                                        @foreach($admin_request_types as $admin_request_type)
                                                            <option value="{{ $admin_request_type->id }}">{{ $admin_request_type->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="fname" class="form-label">نام</label>
                                                    <input type="text" name="fname" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="mobile" class="form-label">موبایل</label>
                                                    <input type="text" name="mobile" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="admin_group_id" class="form-label">گروه کاربری</label>
                                                    <input type="text" name="admin_group_id" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="lname" class="form-label">نام خانوادگی</label>
                                                    <input type="text" name="lname" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2"></div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="p_ncode" class="form-label">کدملی</label>
                                                    <input type="text" name="p_ncode" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 py-2">
                                                <div class="form-group">
                                                    <label for="province_id" class="form-label">استان</label>
                                                    <select id="province_id" name="province_id" class="form-control">
                                                        <option value="" selected></option>
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
                                            <div class="col-sm-4 py-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12 my-2">
                                <div class="card">
                                    <div class="card-header bg-secondary">
                                        مستندات درخواست
                                    </div>
                                    <div class="card-body">
                                        <label for="file" class="d-block" style="cursor: pointer;">
                                            <div style="width: 200px;height: 200px;background-color: #DDD;" class="m-auto mb-3 rounded-circle"></div>
                                            <p class="fw-bold text-center">
                                                آپلود مدارک
                                                <i class="fa fa-plus-circle text-info"></i>
                                            </p>
                                        </label>
                                        <input type="file" name="file" id="file" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 m-auto">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="submit" class="btn btn-primary d-block w-100">ذخیره سازی</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn btn-secondary d-block w-100" data-dismiss="modal">بستن</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                let data = ajax_data(conditions, 'POST', '/admin/request');

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

        function changeStatus(item) {
            $(item).parent().parent().find('.nav-link').each(function (element) {
                if ($($(item).parent().parent().find('.nav-link')[element]).hasClass('active')) {
                    $($(item).parent().parent().find('.nav-link')[element]).removeClass('active')
                }
            });
            $(item).addClass('active');
            $('#requestStatus').val($(item).data('status'));
            changeCondition('#requestStatus', 'status');
            setFilters();
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

        function getRequest(id) {
            $('#exampleModal').modal('show')
            let result = ajax_data({}, 'GET', '/admin/request/show/' + id);
            if (result.status) {
                $('#modalId').val(id);
                $('#modalName').text(result.data.fname);
                $('#modalLname').text(result.data.lname);
                $('#modalMobile').text(result.data.mobile);
                $('#modalP_ncode').text(result.data.p_ncode);
                $('#modalRequestType').text(result.data.request_type);
                $('#modalRole').text(result.data.admin_type);
                $('#modalDeny').val(result.data.deny_description);

                if (result.data.status == 0) {
                    $('#modalStatus').text('در انتظار تایید کارشناس');
                    if ($('#modalButtons').hasClass('d-none')) {
                        $('#modalButtons').removeClass('d-none')
                    }
                }

                if (result.data.status == 1) {
                    $('#modalStatus').text('تایید شده')
                    $('#modalButtons').addClass('d-none');
                    $('#modalDenyBox').hide();
                }

                if (result.data.status == 2) {
                    $('#modalStatus').text('رد شده')
                    $('#modalButtons').addClass('d-none');
                    $('#modalDenyBox').show();
                }
            }
        }

        $('#denyRequest').click(function () {
            $('#modalDenyBox').show(300);
        })

        $('#confirmRequest').click(function () {
            $('#modalDenyBox').hide(300);

            updateRequest($('#modalId').val(), 1);
        })

        $('#denySave').click(function () {
            updateRequest($('#modalId').val(), 2);
        })

        function updateRequest(id, status) {
            let result = ajax_data({
                status: status,
                deny_description: status == 2 ? $('#modalDeny').val() : null
            }, 'POST', '/admin/request/status/' + id);

            if (result.status) {
                alert(result.data.message);
                $('#exampleModal').modal('hide')
                makeTable();
            }
        }

        function addModal() {
            console.log(1)
            $('#addRequest').modal('show')
        }

    </script>
    {{--    <script type="text/javascript" src="{{ url('/assets/js/admin/dt.js') }}?time={{ time() }}"></script>--}}
@endsection

