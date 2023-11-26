<div class="app-form app-form-previouslicense inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات پروانه قبلی</h5>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <p>در این بخش اطلاعات پروانه موجود را وارد کنید.</p>
        </div>
    </div>
    <form id="form-previouslicense" action="/application/previous-license-info-submit">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="plic-licenseno">شماره پروانه اشتغال به کار: <i class="fa fa-star text-required"></i></label> 
                            <input id="plic-licenseno" name="licenseno" type="text" class="form-control text-end ltr" required="required" >
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="plic-licenseserialno">شماره سریال پروانه: <i class="fa fa-star text-required"></i></label> 
                            <input id="plic-licenseserialno" name="licenseserialno" type="text" class="form-control text-end ltr" required="required" >
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="plic-firstlicensedate">تاریخ صدور اولین پروانه:  <i class="fa fa-star text-required"></i></label> 
                            <input id="plic-firstlicensedate" name="firstlicensedate" type="text" class="form-control datepicker" maxlength="11" required="required" >
                        </div> 
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="plic-expirationdate">تاریخ پایان اعتبار: <i class="fa fa-star text-required"></i></label> 
                            <input id="plic-expirationdate" name="expirationdate" type="text" class="form-control datepicker" required="required" >
                        </div> 
                    </div>                            
                </div>
            </div>
        </div>        
    </form>
    <div class="row mt-5">
        <div class="col-12">
            <p>پایه و صلاحیت مندرج در پروانه فعلی: <i class="fa fa-star text-required"></i></p>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                       
            <div class="row">
                <div class="col-12">
                    <table id="previous-license-table-form" class="table table-striped cell-border table-bordered w-100" >
                        <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>صلاحیت</th>
                                <th>پایه</th>
                                <th>تاریخ احراز صلاحیت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <select id="plic-auth" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($license_auth as $la)
                                        <option value="{{ $la->lic_auth_id }}">{{ $la->lic_auth_title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="plic-basis" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($license_basis as $lb)
                                        <option value="{{ $lb->lic_basis_id }}">{{ $lb->lic_basis_title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input id="plic-date" type="text" class="form-control form-control-sm datepicker" maxlength="10" required="required"></td>
                                <td><button type="button" class="btn btn-secondary rounded-pill btn-sm px-3 py-1" id="new-plic-basis-auth-record-btn">ثبت</button></td>
                            </tr>
                        </tbody>
                    </table>                    
                </div>
            </div>
        </div>
    </div>

    <div class="row my-5">
        <div class="col-6 position-relative">
            <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-previouslicense">قبلی</button>
        </div>
        <div class="col-6">
            <button type="submit" class="btn btn-default border border-dark btn-form" form="form-previouslicense">مشاهده پیش نمایش</button>
        </div>
    </div>
</div>