<div class="app-form app-form-preview inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست</h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>

    <!-- اطلاعات حقوقی -->
    <div id="preview-org-info" class="hidden">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات حقوقی</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-legalperson-orgname">نام شرکت</label> 
                            <input id="preview-legalperson-orgname" type="text" class="form-control"  disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-legalperson-ncode">شناسه ملی شرکت</label> 
                            <input id="preview-legalperson-ncode" type="text" class="form-control"  disabled >
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-legalperson-regnum">شماره ثبت</label> 
                            <input id="preview-legalperson-regnum" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-legalperson-regdate">تاریخ ثبت</label> 
                            <input id="preview-legalperson-regdate" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-legalperson-establishdate">تاریخ تاسیس</label> 
                            <input id="preview-legalperson-establishdate" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات هویتی -->
    <div id="preview-person-info">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات هویتی</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-persinfo-name">نام</label> 
                            <input id="preview-persinfo-name" type="text" class="form-control" disabled>
                            </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-persinfo-lname">نام خانوادگی </label> 
                            <input id="preview-persinfo-lname" type="text" class="form-control"disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-persinfo-ncode">کد ملی</label> 
                            <input id="preview-persinfo-ncode" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                </div>
                <div class="row mt-5">                
                    <div class="col-4">                 
                        <div class="form-group">
                            <label for="preview-persinfo-birthplace">محل صدور</label> 
                            <input id="preview-persinfo-birthplace" type="text" class="form-control" disabled>
                        </div> 
                    </div>                    
                    <div class="col-8">
                        <div class="form-group">
                            <label>وضعیت تاهل</label>
                            <div class="pt-2 ps-5">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="preview-persinfo-radio-marital-single" type="radio" disabled>
                                    <label class="form-check-label" for="preview-persinfo-radio-marital-single">مجرد</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="preview-persinfo-radio-marital-married" type="radio" disabled>
                                    <label class="form-check-label" for="preview-persinfo-radio-marital-married">متاهل</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>

    <!-- اطلاعات آدرس و تماس -->
    <div id="preview-contact">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات آدرس و تماس</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-mobile">شماره تلفن همراه</label> 
                            <input id="preview-contact-mobile" class="form-control"  disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-email">آدرس پست الکترونیکی </label> 
                            <input id="preview-contact-email" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-9 col-md-3 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-tel">تلفن ثابت</label> 
                            <input id="preview-contact-tel" type="text" class="form-control" disabled >
                        </div> 
                        
                    </div>
                    <div class="col-xs-3 col-md-1 mt-5 ps-md-0">
                        <div class="form-group">
                            <label for="preview-contact-tel-code">کد</label> 
                            <input id="preview-contact-tel-code"type="text" class="form-control" disabled >
                        </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-postalcode">کدپستی</label> 
                            <input id="preview-contact-postalcode" type="text" class="form-control" disabled >
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">                 
                        <div class="form-group">
                            <label for="preview-contact-province">استان</label> 
                            <input id="preview-contact-province" type="text" class="form-control" disabled>
                        </div> 
                    </div>  
                    <div class="col-xs-12 col-md-4 mt-5">                 
                        <div class="form-group">
                            <label for="preview-contact-city">شهر</label> 
                            <input id="preview-contact-city" type="text" class="form-control" disabled>
                        </div> 
                    </div>  
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-street1">خیابان</label> 
                            <input id="preview-contact-street1" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-street2">کوچه</label> 
                            <input id="preview-contact-street2" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-no">پلاک</label> 
                            <input id="preview-contact-no" type="text" class="form-control" disabled>
                            </div> 
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-floor">طبقه</label> 
                            <input id="preview-contact-floor" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-contact-unit">واحد</label> 
                            <input id="preview-contact-unit" type="text" class="form-control" disabled >
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات بیمه -->
    <div id="preview-insurance-info">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات بیمه</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="form-group">
                            <label>دارای بیمه های اجتماعی می باشم؟</label>
                            <div class="pt-2 ps-5">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="preview-radio-insurance-yes" type="radio" disabled>
                                    <label class="form-check-label" for="preview-radio-insurance-yes">بله</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="preview-radio-insurance-no" type="radio" disabled>
                                    <label class="form-check-label" for="preview-radio-insurance-no">خیر</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5" id="form-insurance-details">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-insurance-instype">نوع بیمه</label> 
                            <input id="preview-insurance-instype" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    {{-- @deprecated <div class="col-4">
                        <div class="form-group">
                            <label for="preview-insurance-insplace">محل پرداخت بیمه </label> 
                            <input id="preview-insurance-insplace" type="text" class="form-control" disabled>
                        </div> 
                    </div> --}}
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-insurance-insoccupation">شغل اصلی</label> 
                            <input id="preview-insurance-insoccupation" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات تحصیلی -->
    <div id="preview-education">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات تحصیلی</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-12">
                        <table id="preview-education-table" class="table table-striped cell-border table-bordered w-100" >
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>مقطع تحصیلی</th>
                                    <th>رشته</th>
                                    <th>گرایش</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات درخواست -->
    <div id="preview-request">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات درخواست</h5>
            </div>
        </div>     
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-req-orgname">نام سازمان</label> 
                            <input id="preview-req-orgname" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-req-province">استان</label> 
                            <input id="preview-req-province" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-req-membership-id">شماره عضویت </label> 
                            <input id="preview-req-membership-id" type="text" class="form-control" disabled>
                        </div> 
                    </div>                    
                </div>
                <div class="row mt-4">
                    <div class="col-8">
                        <div class="form-group">
                            <label for="preview-req-licensetype">نوع پروانه</label> 
                            <input id="preview-req-licensetype" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="preview-req-retype">نوع درخواست</label> 
                            <input id="preview-req-retype" type="text" class="form-control" disabled>
                        </div> 
                    </div>                            
                </div>
            </div>
        </div>        
        <div class="row mt-5">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <p>پایه و حدود صلاحیت مورد نظر برای پروانه جدید:</p>
            </div>
        </div> 
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">            
                <div class="row">
                    <div class="col-12">
                        <table id="preview-request-table-form" class="table table-striped cell-border table-bordered w-100" >
                            <thead>
                                <tr>
                                    <th>شناسه</th>
                                    <th>صلاحیت پروانه جدید</th>
                                    <th>پایه پروانه جدید</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- اطلاعات پروانه قبلی -->
    <div id="preview-previous-license" class="hidden">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات پروانه قبلی</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-plic-licenseno">شماره پروانه اشتغال به کار:</label> 
                            <input id="preview-plic-licenseno" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-plic-licenseserialno">شماره سریال پروانه:</label> 
                            <input id="preview-plic-licenseserialno" type="text" class="form-control" disabled>
                        </div> 
                    </div>
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-plic-firstlicensedate">تاریخ صدور اولین پروانه: </label> 
                            <input id="preview-plic-firstlicensedate" type="text" class="form-control" disabled>
                        </div> 
                    </div>                    
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-4 mt-5">
                        <div class="form-group">
                            <label for="preview-plic-expirationdate">تاریخ پایان اعتبار:</label>
                            <input id="preview-plic-expirationdate" type="text" class="form-control" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <p>پایه و حدود صلاحیت موجود در پروانه:</p>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                       
                <div class="row">
                    <div class="col-12">
                        <table id="preview-previous-license-table" class="table table-striped cell-border table-bordered w-100" >
                            <thead>
                                <tr>
                                    <th>ردیف</th>
                                    <th>صلاحیت</th>
                                    <th>پایه</th>
                                    <th>تاریخ احراز صلاحیت</th>
                                </tr>
                            </thead>
                            <tbody>                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="preview-previous-license-upload" class="hidden">
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">تصویر پروانه قبلی</h5>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-6">  
                        <div class="form-group">
                            <label for="preview-image-front">تصویر رو پروانه</label>                                       
                             <img id="preview-image-front" class="w-100" src="" alt="">
                        </div>                  
                    </div>                      
                    <div class="col-6">  
                        <div class="form-group">
                            <label for="preview-image-rear">تصویر پشت پروانه</label>                                       
                            <img id="preview-image-rear" class="w-100" src="" alt="">
                        </div>                  
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <form id="form-preview" action="/application/preview-submit/{{ $application_id  }}">
        <div class="row my-5"></div>
        <div class="row my-5">
            <div class="col-6 position-relative">
                <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-preview" >ویرایش</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-confirm">تایید</button>
            </div>
        </div>
    </form>
</div>