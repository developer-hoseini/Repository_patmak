@extends('admin.layout')

@section('title',  trans('app.page_title') . ' | لیست درخواست ها' )

@section('content')

    <div class="mt-5" id="app_app">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1"></div>

                <div class="col-sm-5">
                    <div class="row">
                        <div class="col-sm-12 d-flex align-items-center justify-content-between">
                            <h4 class="fw-bold">ویرایش کاربر</h4>
                        </div>


                        <div class="col-sm-12">
                            <form action="{{ route('admin.admin.update',['admin' => $admin->admin_id]) }}"
                                  method="post">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <div class="row">
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="fname" class="form-label fw-bold">
                                                نام
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <input type="text" id="fname" name="fname"
                                                   class="form-control form-control-lg" value="{{ $admin->fname }}">
                                            @if($errors->has('fname'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('fname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="province_id" class="form-label fw-bold">استان</label>
                                            <select name="province_id" class="form-control form-control-lg"
                                                    id="province_id">
                                                <option value=""></option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->province_id }}"
                                                            @if($admin->province_id == $province->province_id) selected @endif>{{ $province->province_title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('province_id'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('province_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="lname" class="form-label fw-bold">
                                                نام خانوادگی
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <input type="text" id="lname" name="lname"
                                                   class="form-control form-control-lg"
                                                   value="{{ $admin->lname }}">
                                            @if($errors->has('lname'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('lname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="mobile" class="form-label fw-bold">
                                                شماره موبایل
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <input type="text" id="mobile" name="mobile"
                                                   class="form-control form-control-lg"
                                                   value="{{ $admin->mobile }}">
                                            @if($errors->has('mobile'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('mobile') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="p_ncode" class="form-label fw-bold">
                                                کدملی
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <input type="text" id="p_ncode" name="p_ncode"
                                                   class="form-control form-control-lg"
                                                   value="{{ $admin->p_ncode }}">
                                            @if($errors->has('p_ncode'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('p_ncode') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="sex" class="form-label fw-bold">
                                                جنسیت
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <select name="sex" class="form-control form-control-lg" id="sex">
                                                <option value=""></option>
                                                <option value="0" @if($admin->sex == 0) selected @endif>آقا</option>
                                                <option value="1" @if($admin->sex == 1) selected @endif>خانم</option>
                                            </select>
                                            @if($errors->has('sex'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('sex') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="admin_type_id" class="form-label fw-bold">
                                                گروه کاربری
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <select name="admin_type_id" class="form-control form-control-lg"
                                                    id="admin_type_id">
                                                <option value=""></option>
                                                @foreach($admin_types as $admin_type)
                                                    <option value="{{ $admin_type->type_id }}"
                                                            @if($admin->admin_type_id == $admin_type->type_id) selected @endif>{{ $admin_type->type_title }}</option>
                                                @endforeach
                                            </select>
                                            @if($errors->has('admin_type_id'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('admin_type_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="is_active" class="form-label fw-bold">
                                                وضعیت کاربری
                                            </label>
                                            <select name="is_active" class="form-control form-control-lg"
                                                    id="is_active">
                                                <option value=""></option>
                                                <option value="0" @if($admin->is_active == 0) selected @endif>غیرفعال
                                                </option>
                                                <option value="1" @if($admin->is_active == 1) selected @endif>فعال
                                                </option>
                                            </select>
                                            @if($errors->has('is_active'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('is_active') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="username" class="form-label fw-bold">
                                                نام کاربری
                                            </label>
                                            <input type="text" id="username" name="username"
                                                   class="form-control form-control-lg"
                                                   disabled
                                                   value="{{ $admin->username }}">
                                            @if($errors->has('username'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6 my-3">
                                        <div class="form-group">
                                            <label for="password" class="form-label fw-bold">
                                                رمزعبور
                                                <i class="fa fa-star text-required"></i>
                                            </label>
                                            <input type="password" id="password" name="password"
                                                   class="form-control form-control-lg">
                                            @if($errors->has('password'))
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="alert alert-info text-dark fw-bold p-2 mb-2">
                                                    سطح دسترسی گزارشگیری
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="card rounded-3">
                                                    <div class="card-body">
                                                        <ul class="nav nav-tabs d-flex justify-content-center" id="myTab"
                                                            role="tablist">
                                                            @foreach($groups as $index => $group)
                                                                <li class="nav-item flex-fill" role="presentation">
                                                                    <button class="nav-link bg-info d-block text-white w-100 @if($loop->first) active @endif"
                                                                            id="{{ 'group-'.$index }}-tab" data-bs-toggle="tab"
                                                                            data-bs-target="#{{ 'group-'.$index }}" type="button"
                                                                            role="tab" aria-controls="{{ 'group-'.$index }}"
                                                                            aria-selected="true">{{ $group->title }}</button>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="tab-content" id="myTabContent">
                                                            @foreach($groups as $index => $group)
                                                                <div class="tab-pane py-3 fade @if($loop->first) show active @endif"
                                                                     id="{{ 'group-'.$index }}" role="tabpanel"
                                                                     aria-labelledby="{{ 'group-'.$index }}-tab">
                                                                    <div class="row">
                                                                        @foreach ($group->permissions as $key => $permission)
                                                                            <div class="col-sm-6">
                                                                                <label class="control control-checkbox control-dark">
                                                                                    <input type="checkbox"
                                                                                           @if(in_array($permission->id,array_column($admin->permissions()->select('ref_permissions.id')->get()->toArray(),'id'))) checked @endif
                                                                                           name="permissions[{{ $key }}]"
                                                                                           value="{{ $permission->id }}" class="mx-2" style="vertical-align: middle;transform: scale(1.3)">
                                                                                    <span>{{ $permission->title }}</span>
                                                                                    <span class="control-icon"></span>
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 my-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ route('admin.admin.index') }}" target="_self"
                                           class="text-dark text-decoration-none mx-3">بازگشت</a>
                                        <button class="btn btn-success btn-lg mx-3" type="submit">ویرایش
                                            کاربر
                                        </button>
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
    <script type="text/javascript">
        $("form").submit(function () {
            $("form").attr('target', '_self');
            return true;
        });
    </script>
@endsection