<div class="app-form app-form-previouslicense-upload inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2">تصویر پروانه قبلی</h5>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-12">
            <p>در این بخش تصویر پروانه را بارگزاری کنید. حداکثر فایل قابل بارگزاری ۵۰۰ کیلوبایت می باشد. همچنین فایل باید از نوع عکس و یکی از فرمت های PNG یا JPG باشد.</p>
        </div>
    </div>
    <form id="form-previouslicense-upload" action="">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">                
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-4">  
                        <div class="form-group">
                            <label for="f2-ncode">تصویر رو پروانه <i class="fa fa-star text-required"></i></label>                                       
                            <div class="input-group">                                
                                <input type="text" class="form-control" readonly >
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        انتخاب فایل <input id="old-license-front" type="file" style="display: none;" accept="image/*" multiple>
                                    </span >
                                </label>
                            </div>  
                        </div>                  
                    </div>                      
                    <div class="col-xs-12 col-md-6 mt-4">  
                        <div class="form-group">
                            <label for="f2-ncode">تصویر پشت پروانه <i class="fa fa-star text-required"></i></label>                                       
                            <div class="input-group">                                
                                <input type="text" class="form-control" readonly >
                                <label class="input-group-btn">
                                    <span class="btn btn-primary">
                                        انتخاب فایل <input id="old-license-rear" type="file" style="display: none;" accept="image/*" multiple>
                                    </span >
                                </label>
                            </div>  
                        </div>                  
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-6 position-relative">
                
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default border border-dark btn-form">بعدی</button>
            </div>
        </div>

    </form>
</div>