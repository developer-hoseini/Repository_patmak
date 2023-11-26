<div class="app-form app-form-request inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>

        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات درخواست</h5>
        </div>
    </div>
    <div class="row mt-1"></div>
    <form id="form-request" action="/application/request-info-submit">        
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="req-orgname">نام سازمان <i class="fa fa-star text-required"></i></label> 
                            <input id="req-orgname" name="orgname" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="req-province">استان <i class="fa fa-star text-required"></i></label> 
                            <input id="req-province" name="province" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="req-membership-id" ><span id="req-membership-id-field-label">شماره عضویت سازمان </span> <i class="fa fa-star text-required"></i></label> 
                            <input id="req-membership-id" name="membershipid" type="text" class="form-control text-end ltr" maxlength="15" required="required" >
                          </div> 
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="req-licensetype">نوع پروانه <i class="fa fa-star text-required"></i></label> 
                            <input id="req-licensetype" name="licensetype" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="req-retype">نوع درخواست <i class="fa fa-star text-required"></i></label> 
                            <input id="req-retype" name="reqtype" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>  
                    <div class="col-12 col-md-4 mt-4 hidden" id="req-anjoman-membership-id-field">
                        <div class="form-group">
                            <label for="req-anjoman-membership-id">شماره عضویت انجمن </label> 
                            <input id="req-anjoman-membership-id" name="anjomanmembershipid" type="text" class="form-control text-end ltr" maxlength="30" >
                          </div> 
                    </div>                           
                </div>
            </div>
        </div>        
    </form>
    <div class="row mt-5">
        <div class="col-12">
            <p>پایه و صلاحیت مورد نظر برای پروانه جدید: <i class="fa fa-star text-required"></i></p>
        </div>
    </div> 
    <div class="row mt-2">
        <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">            
            <div class="row">
                <div class="col-12">
                    <table id="request-table-form" class="table table-striped cell-border table-bordered w-100" >
                        <thead>
                            <tr>
                                <th>شناسه</th>
                                <th>صلاحیت پروانه جدید</th>
                                <th>پایه پروانه جدید</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td>
                                    <select id="req-lic-auth" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($license_auth as $la)
                                        <option value="{{ $la->lic_auth_id }}">{{ $la->lic_auth_title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="req-lic-basis" class="form-select form-select-sm" aria-label="Default select example">
                                        <option value="">انتخاب کنید</option>
                                        @foreach ($license_basis as $lb)
                                        <option value="{{ $lb->lic_basis_id }}">{{ $lb->lic_basis_title }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><button type="button" class="btn btn-secondary rounded-pill btn-sm px-3 py-1" id="new-request-basis-auth-record-btn">ثبت</button></td>
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
            <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-request" >قبلی</button>
        </div>
        <div class="col-6">
            <button type="submit" class="btn btn-default border border-dark btn-form" form="form-request" id="request-form-submit-btn">بعدی</button>
        </div>
    </div>

</div>