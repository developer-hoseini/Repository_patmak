<div class="app-form app-form-personalinfo inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>

        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات هویتی</h5>
        </div>
    </div>
    <form id="form-personalinfo" action="/application/personal-info-submit">
        <div class="row">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row mt-4">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="persinfo-name">نام <i class="fa fa-star text-required"></i></label> 
                            <input id="persinfo-name" name="name" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="persinfo-lname">نام خانوادگی  <i class="fa fa-star text-required"></i></label> 
                            <input id="persinfo-lname" name="mobile" type="text" class="form-control" maxlength="11" required="required" disabled>
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="persinfo-ncode">کد ملی <i class="fa fa-star text-required"></i></label> 
                            <input id="persinfo-ncode" name="ncode" type="text" class="form-control" required="required" disabled>
                          </div> 
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-12 col-md-4 mt-4">                 
                        <div class="form-group">
                            <label for="persinfo-birthplace">استان محل صدور شناسنامه <i class="fa fa-star text-required"></i></label> 
                            <select id="persinfo-birthplace" name="birthplace" class="form-select">
                                <option value="">انتخاب کنید</option> 
                                @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 mt-4">
                        <div class="form-group">
                            <label>وضعیت تاهل <i class="fa fa-star text-required"></i></label>
                            <div class="pt-2 ps-5">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="persinfo-radio-marital-single" type="radio" name="marital_status" value="0">
                                    <label class="form-check-label" for="persinfo-radio-marital-single">مجرد</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="persinfo-radio-marital-married" type="radio" name="marital_status" value="1">
                                    <label class="form-check-label" for="persinfo-radio-marital-married">متاهل</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                      
            </div>
        </div>

        <div class="row my-5"></div>

        <div class="row my-5">
            <div class="col-6 position-relative">
                <button type="button" id="previous-form-btn-personalinfo" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0 hidden" data-current-form="form-personalinfo">قبلی</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default border border-dark btn-form">بعدی</button>
            </div>
        </div>

    </form>
</div>