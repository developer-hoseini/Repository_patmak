<div class="app-form app-form-primary-info inactive-form">
    <div class="row mt-5">
        <div class="col-12">
            <h5 class="text-right">ورود اطلاعات اولیه</h5>
        </div>
    </div>

    <form id="form-primary-info" action="/application/primary-info-submit">
        <div class="row mt-2">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <table>
                    <tr>
                        <td>نوع شخص:</td>
                        <td class="ps-4 hidden"><label for="regular-person">حقیقی <input id="regular-person" type="radio" name="persontype" value="regular" /></label></td>
                        <td class="ps-3 hidden"><label for="legal-person">حقوقی <input id="legal-person" type="radio" name="persontype" value="legal"  /></label></td>
                        <td class="ps-3 hidden"><label for="office-person">حقوقی (دفاتر مهندسی) <input id="office-person" type="radio" name="persontype" value="legal"  /></label></td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- حقیقی -->
        <div class="row mt-2 hidden" id="regular-person-form">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-ncode1">کد ملی <i class="fa fa-star text-required"></i></label>
                            <input id="pi-ncode1" name="ncode1" type="text" class="form-control" value="{{ $user->ncode }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-mobile1">شماره تلفن همراه <i class="fa fa-star text-required"></i></label>
                            <input id="pi-mobile1" name="mobile1" type="text" class="form-control" value="{{ $user->mobile }}" disabled>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-birthdate1">تاریخ تولد <i class="fa fa-star text-required"></i></label>
                            <input id="pi-birthdate1" name="birthdate1" type="text" class="form-control datepicker" maxlength="10" >
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-requesttype1">نوع درخواست <i class="fa fa-star text-required"></i> <i class="fa fa-info-circle text-secondary"></i></label>
                            <select id="pi-requesttype1" name="requesttype1" class="form-select requesttypes">
                                <option value="">انتخاب کنید</option>
                                @foreach ($request_types as $rt)
                                    <option value="{{ $rt->req_type_id }}">{{ $rt->req_type_title }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-licensetype1">نوع پروانه <i class="fa fa-star text-required"></i></label>
                            <select id="pi-licensetype1" name="licensetype1" class="form-select licensetypeselect">
                                <option value="">انتخاب کنید</option>
                                @foreach ($license_types as $lt)
                                    @if ($lt->person_type_id == 1)
                                        <option value="{{ $lt->license_type_id }}">{{ $lt->license_type_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-studyfield1">عنوان رشته مدرک تحصیلی <i class="fa fa-star text-required"></i></label>
                            <select id="pi-studyfield1" name="studyfield1" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($study_fields as $sf)
                                    @if ($sf->education_grade_id == 0)
                                        <option value="{{ $sf->study_field_id }}">{{ $sf->study_field_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                          </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-morg1">عضویت در <i class="fa fa-star text-required"></i></label>
                            <select id="pi-morg1" name="morg1" class="form-select pi-morg-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($mrud_organizations as $morg)
                                    @if($morg->person_type_id == 1)
                                        <option value="{{ $morg->morg_id }}">{{ $morg->morg_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-province1">استان محل عضویت <span class="sub-label">(استان مقصد برای عضو انتقالی)</span> <i class="fa fa-star text-required"></i></label>
                            <select id="pi-province1" name="province1" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_tranfered" value="" id="pi-transfer" >
                                <label class="form-check-label" for="pi-transfer">عضو انتقالی (استان مبدا/مقصد) هستم.</label>
                             </div>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5 hidden" id="transfer-field">
                        <div class="form-group">
                            <label for="pi-province12">استان محل پروانه اشتغال <span class="sub-label">(استان مبدا برای عضو انتقالی)</span> <i class="fa fa-star text-required"></i></label>
                            <select id="pi-province12" name="province12" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-12 mt-2">
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="old" value="" id="old-licence-inuse" >
                                <label class="form-check-label" for="old-licence-inuse">پروانه ام قدیمی است و شماره پروانه اشتغال به کار ندارم.</label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row mt-5">
                    <div class="col-12">
                        <i class="fa fa-info-circle text-secondary"></i> تعرفه ها بر اساس قانون بودجه سال 1401 و تعرفه مابه تفاوت سال 1400 نسبت به سال 1399 بر اساس قانون بودجه ی سال 1400دریافت میگردد.
                    </div>
                </div>

            </div>
        </div><!-- #regular-person-form -->

        <!-- حقوقی -->
        <div class="row mt-2 hidden" id="legal-person-form">
            <div class="col-xs-12 col-md-10 offset-md-1 col-lg-8 offset-lg-2">
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-orgncode">شناسه ملی <span class="sub-label">برای مجوز دفتر مهندسی شماره ثبت وارد شود</span><i class="fa fa-star text-required"></i></label>
                            <input id="pi-orgncode" name="orgncode" type="text" class="form-control" maxlength="11" >
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-ncode2">کد ملی <span class="sub-label">مدیرعامل/رییس موسسه (مسئول واحد فنی )/مسئول دفتر</span> <i class="fa fa-star text-required"></i></label>
                            <input id="pi-ncode2" name="ncode2" type="text" class="form-control" value="{{ $user->ncode }}" disabled>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-mobile2">شماره تلفن همراه مدیر عامل <i class="fa fa-star text-required"></i></label>
                            <input id="pi-mobile2" name="mobile2" type="text" class="form-control" value="{{ $user->mobile }}" disabled>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-birthdate2">تاریخ تولد <i class="fa fa-star text-required"></i></label>
                            <input id="pi-birthdate2" name="birthdate2" type="text" class="form-control datepicker" maxlength="10" >
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-requesttype2">نوع درخواست <i class="fa fa-star text-required"></i>  <i class="fa fa-info-circle text-secondary"></i></label>
                            <select id="pi-requesttype2" name="requesttype2" class="form-select requesttypes">
                                <option value="">انتخاب کنید</option>
                                @foreach ($request_types as $rt)
                                <option value="{{ $rt->req_type_id }}">{{ $rt->req_type_title }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-morg2">عضویت در <i class="fa fa-star text-required"></i></label>
                            <select id="pi-morg2" name="morg2" class="form-select pi-morg-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($mrud_organizations as $morg)
                                    @if($morg->person_type_id == 2)
                                        <option value="{{ $morg->morg_id }}">{{ $morg->morg_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-province2">استان محل عضویت <i class="fa fa-star text-required"></i></label>
                            <select id="pi-province2" name="province2" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($provinces as $province)
                                <option value="{{ $province->province_id }}">{{ $province->province_title }}</option>
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-licensetype2">نوع پروانه <i class="fa fa-star text-required"></i></label>
                            <select id="pi-licensetype2" name="licensetype2" class="form-select licensetypeselect">
                                <option value="">انتخاب کنید</option>
                                @foreach ($license_types as $lt)
                                    @if($user->person_type_id == 2)
                                        @if ($lt->person_type_id == 2 &&  ($lt->license_type_id != 205 || $lt->license_type_id != 206))
                                            <option value="{{ $lt->license_type_id }}">{{ $lt->license_type_title }}</option>
                                        @endif
                                    @else
                                        @if ($lt->person_type_id == 2 &&  ($lt->license_type_id == 205 || $lt->license_type_id == 206))
                                            <option value="{{ $lt->license_type_id }}">{{ $lt->license_type_title }}</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-studyfield2"><small>مدرک تحصیلی <span class="sub-label">مدیرعامل/رییس موسسه(مسئول واحد فنی)/مسئول دفتر</span></small> <i class="fa fa-star text-required"></i></label>
                            <select id="pi-studyfield2" name="studyfield2" class="form-select">
                                <option value="">انتخاب کنید</option>
                                @foreach ($study_fields as $sf)
                                    @if ($sf->education_grade_id == 0)
                                        <option value="{{ $sf->study_field_id }}">{{ $sf->study_field_title }}</option>
                                    @endif
                                @endforeach
                            </select>
                          </div>
                    </div>
                    <div class="col-xs-12 col-md-6 mt-5">
                        <div class="form-group">
                            <label for="pi-orgregdate">تاریخ ثبت دفتر <span class="sub-label">فقط برای انواع مجوز دفتر مهندسی</span></label>
                            <input id="pi-orgregdate" name="orgregdate" type="text" class="form-control datepicker" maxlength="10" >
                        </div>
                    </div>
                </div>

                <div class="row mt-5">
                    <div class="col-12">
                        <i class="fa fa-info-circle text-secondary"></i> تعرفه ها بر اساس قانون بودجه سال 1401 و تعرفه مابه تفاوت سال 1400 نسبت به سال 1399 بر اساس قانون بودجه ی سال 1400دریافت میگردد.
                    </div>
                </div>

            </div>
        </div>

        <div class="row my-5 text-center hidden" id="primary-info-formconform-btn">
            <div class="col-12 col-md-4 offset-md-4">
                <button type="submit" class="btn btn-confirm">تایید</button>
            </div>
        </div>

    </form>
</div>