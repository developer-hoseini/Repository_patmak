<div class="app-form app-form-insurance inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">اطلاعات بیمه اجباری</h5>
        </div>
    </div>
    <form id="form-insurance" action="/application/insurance-info-submit">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-12 mt-4">
                        <div class="form-group">
                            <label>آیا دارای بیمه پایه اجباری می باشید؟</label>
                            <div class="pt-2 ps-5">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="radio-insurance-yes" type="radio" name="haveins" value="1" required="required">
                                    <label class="form-check-label" for="radio-insurance-yes">بله</label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                    <input class="form-check-input" id="radio-insurance-no" type="radio" name="haveins" value="0">
                                    <label class="form-check-label" for="radio-insurance-no">خیر</label>
                                  </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row  hidden" id="form-insurance-details">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="insurance-instype">نوع بیمه <i class="fa fa-star text-required"></i></label> 
                            <input id="insurance-instype" name="instype" type="text" class="form-control" required="required">
                        </div> 
                    </div>                    
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="insurance-insoccupation">شغل اصلی</label> 
                            <input id="insurance-insoccupation" name="insoccupation" type="text" class="form-control" >
                          </div> 
                    </div>
                    <div class="col-12 col-md-4 mt-4 hidden">
                        <div class="form-group">
                            <label for="insurance-insplace">محل پرداخت بیمه  <i class="fa fa-star text-required"></i></label> 
                            <input id="insurance-insplace" name="insplace" type="text" class="form-control" value="-" required="required">
                          </div> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5"></div>

        <div class="row my-5">
            <div class="col-6 position-relative">
                <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-insurance">قبلی</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default border border-dark btn-form">بعدی</button>
            </div>
        </div>

    </form>
</div>