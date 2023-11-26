<div class="app-form app-form-contact inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">تکمیل اطلاعات درخواست &#10095;<span class="request-type-holder"></span></h5>
            <h5 class="text-right">مبلغ درخواست : <span class="request-cost-holder badge bg-warning text-dark"></span></h5>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2 hidden" id="contact-form-title-for-regular-person">اطلاعات آدرس و تماس محل سکونت</h5>
            <h5 class="text-right bg-secondary text-white d-inline rounded-pill px-4 py-2 hidden" id="contact-form-title-for-legal-person">اطلاعات آدرس و تماس شرکت/موسسه/دفتر مهندسی</h5>
        </div>
    </div>
    <form id="form-contact" action="/application/contact-info-submit">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <div class="row ">
                    <div class="col-xs-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-mobile">شماره تلفن همراه <i class="fa fa-star text-required"></i></label>
                            <input id="contact-mobile" name="mobile" type="text" class="form-control"  disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-email">آدرس پست الکترونیکی</label>
                            <input id="contact-email" dir="ltr" name="email" type="text" class="form-control">
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-tel">تلفن ثابت <span class="sub-label">با کد شهر</span> <i class="fa fa-star text-required"></i></label>
                            <input id="contact-tel" dir="ltr" name="tel" type="text" class="form-control" required="required" >
                        </div>
                    </div>
{{--                    <div class="col-3 col-md-1 mt-4 ps-md-0">--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="contact-telcode">کد <i class="fa fa-star text-required"></i></label> --}}
{{--                            <input id="contact-telcode" name="telcode" type="text" class="form-control" required="required" >--}}
{{--                          </div> --}}
{{--                    </div>--}}
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-postalcode">کدپستی <i class="fa fa-star text-required"></i></label>
                            <input id="contact-postalcode" dir="ltr" name="postalcode" type="text" class="form-control" maxlength="10" required="required" >
                          </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-province">استان <i class="fa fa-star text-required"></i></label>
                            <select id="contact-province" name="province" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-city">شهر <i class="fa fa-star text-required"></i></label>
                            <select id="contact-city" name="city" class="form-select">
                                <option value="">انتخاب کنید</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-street1">خیابان <i class="fa fa-star text-required"></i></label>
                            <input id="contact-street1" name="street1" type="text" class="form-control" required="required">
                          </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-street2">کوچه</label>
                            <input id="contact-street2" name="street2" type="text" class="form-control" >
                          </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-no">پلاک</label>
                            <input id="contact-no" name="no" type="text" class="form-control"  >
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-floor">طبقه</label>
                            <input id="contact-floor" name="floor" type="text" class="form-control" >
                          </div>
                    </div>
                    <div class="col-12 col-md-4 mt-4">
                        <div class="form-group">
                            <label for="contact-unit">واحد</label>
                            <input id="contact-unit" name="unit" type="text" class="form-control"  >
                          </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-2 hidden" id="contact-form-extra">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <div class="row">
                    <div class="col-12 col-md-8 mt-3">
                        <div class="form-group">
                            <label for="contact-work-address">آدرس محل کار <i class="fa fa-star text-required"></i></label>
                            <input id="contact-work-address" name="work_address" type="text" class="form-control" >
                          </div>
                    </div>
                    <div class="col-12 col-md-4 mt-3">
                        <div class="form-group">
                            <label for="contact-work-tel-number">تلفن محل کار <span class="sub-label">با کد شهر</span> <i class="fa fa-star text-required"></i></label>
                            <input id="contact-work-tel-number" name="work_tel_number" type="text" class="form-control"  >
                          </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5"></div>

        <div class="row my-5">
            <div class="col-6 position-relative">
                <button type="button" class="previous-form-btn btn btn-default border border-dark btn-form position-absolute end-0" data-current-form="form-contact" >قبلی</button>
            </div>
            <div class="col-6">
                <button type="submit" class="btn btn-default border border-dark btn-form">بعدی</button>
            </div>
        </div>

    </form>


</div>
