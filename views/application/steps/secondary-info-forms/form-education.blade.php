<div class="app-form app-form-education inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات تحصیلی</h5>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <p>لطفا اطلاعات تمام مدارک تحصیلی خود را وارد کنید.</p>
            <p><i class="fa fa-info-circle text-secondary"></i> درج مدارک تحصیلی کارشناسی ارشد و دکتری که در رشته های اصلی مهندسی ساختمان مطابق مصوبات کمیسیون ماده 7 قانون نظام مهندسی و کنترل ساختمان (کمیسیون هم ارزی رشته ها)باشد ملاک عمل خواهد بود.</p>
        </div>
    </div>   
    <form id="form-education" action="/application/education-info-submit">
    </form> 
    <div class="row mt-2">
        <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
            <div class="row mt-4">
                <div class="col-12">
                    <table id="education-table-form" class="table table-striped cell-border table-bordered w-100" >
                        <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>مقطع تحصیلی</th>
                                <th>رشته</th>
                                <th>گرایش</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <select id="edu-grade" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($education_grades as $eg)
                                        <option value="{{ $eg->education_grade_id }}">{{ $eg->education_grade_title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="edu-field" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                    </select>
                                </td>
                                <td><input id="edu-area" type="text" class="form-control form-control-sm"></td>
                                <td><button type="button" class="btn btn-secondary rounded-pill btn-sm px-3 py-1" id="new-education-record-btn">ثبت</button></td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row my-5"></div>

    <div class="row my-5">
        <div class="col-6 position-relative">
            <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-education" >قبلی</button>
        </div>
        <div class="col-6">
            <button type="submit" class="btn btn-default border border-dark btn-form" form="form-education">بعدی</button>
        </div>
    </div>
    
</div>