<div class="app-form app-form-legalperson inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>

        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات حقوقی</h5>
        </div>
    </div>
    <form id="form-legalperson" action="/application/legal-person-submit">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-12 col-md-4 mt-4" id="legalperson-orgname-div">
                        <div class="form-group">
                            <label for="legalperson-orgname">نام شرکت/موسسه <i class="fa fa-star text-required"></i></label> 
                            <input id="legalperson-orgname" name="orgname" type="text" class="form-control"  disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group" id="legalperson-ncode-div">
                            <label for="legalperson-ncode">شناسه ملی شرکت <i class="fa fa-star text-required"></i></label> 
                            <input id="legalperson-ncode" name="ncode" type="text" class="form-control" maxlength="11" disabled >
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4"  id="legalperson-establishdate-div" >
                        <div class="form-group">
                            <label for="legalperson-establishdate">تاریخ تاسیس <i class="fa fa-star text-required"></i></label> 
                            <input id="legalperson-establishdate" name="establishdate" type="text" class="form-control" maxlength="11" required="required" disabled>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="legalperson-regdate">تاریخ ثبت <i class="fa fa-star text-required"></i></label> 
                            <input id="legalperson-regdate" name="regdate" type="text" class="form-control datepicker" maxlength="10" required="required" disabled>
                        </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="legalperson-regnum">شماره ثبت <i class="fa fa-star text-required"></i></label> 
                            <input id="legalperson-regnum" name="regnum" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row my-5"></div>

        <div class="row my-5">
            <div class="col-6 position-relative">
                <button type="button" id="previous-form-btn-legalperson" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0 hidden" data-current-form="form-legalperson">قبلی</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default border border-dark btn-form">بعدی</button>
            </div>
        </div>

    </form>
</div>