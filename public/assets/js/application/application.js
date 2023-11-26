// Gloabal variables
var PERSON_TYPE = false;
var PREVIOUS_FORM = null;
var PREVIOUS_LICENSE_EXISTS = false;
var DT_EDU; // holds table of education records
var DT_REQ; // holds table of request records
var DT_PLIC; // holds table of revious license records

let CSRF = $('meta[name="csrf-token"]').attr('content');

var MEMBERSHIP_ID = false;
var EMPLOYMENT_LICENSE_NUMBER = false;

/**
 *
 * Listeners
 *
 */
jQuery(document).ready(function(){

    clearSessionStorage();
    fillFormWithUserHistory();

    // ---------------------------------------------------------------------------------------------------

    jQuery('input[name="persontype"]').click(function(e){
        let person = $('input[name="persontype"]:checked').val();
        if (person === 'regular'){
            PERSON_TYPE = 'regular';
            showRegularPersonForm();
            setContactFormForRegularPerson();
        }
        else {
            PERSON_TYPE = 'legal';
            showLegalPersonForm();
            setContactFormForLegalPerson();
        }
    });

    // ---------------------------------------------------------------------------------------------------

    jQuery('input[name="haveins"]').click(function(e){
        let haveins = $('input[name="haveins"]:checked').val();
        if (haveins === '0'){
            hideInsuranceForm();
        } else {
            showInsuranceForm();
        }
    });

    // ---------------------------------------------------------------------------------------------------

    jQuery('#req-membership-id').on('focus keyup', function(e){
        preventUserToCleanMembershipId();
    });

    // ---------------------------------------------------------------------------------------------------

    jQuery('#plic-licenseno').on('focus keyup', function(e){
        preventUserToCleanEmploymentLicenseNumber();
    });

    // ---------------------------------------------------------------------------------------------------

    /**
     * کسانیکه صدور برای اولین بار را انتحاب میکنند فرم تصویر پروانه قبلی و فرم اطلاعات پروانه قبلی را ندارند
     */
    jQuery(document).on('change', '.requesttypes', function(e){
        let request_type = jQuery(this).val();
        prepareFormsBaseOnRequestType(request_type);
    });

    // ---------------------------------------------------------------------------------------------------

    jQuery('#form-conditions').submit(function(e){
        e.preventDefault();
        checkFormConditions(jQuery('#form-conditions'));
    });

    jQuery('#form-primary-info').submit(function(e){
        e.preventDefault();
        checkFormPrimaryInfo(jQuery('#form-primary-info'));
    });

    jQuery('#form-previouslicense-upload').submit(function(e){
        e.preventDefault();
        checkFormPreviousLicenseUploadFiles(jQuery('#form-previouslicense-upload'));
    });

    jQuery('#form-legalperson').submit(function(e){
        e.preventDefault();
        checkFormLegalPerson(jQuery('#form-legalperson'));
    });

    jQuery('#form-personalinfo').submit(function(e){
        e.preventDefault();
        checkFormPersonalInfo(jQuery('#form-personalinfo'));
    });

    jQuery('#form-contact').submit(function(e){
        e.preventDefault();
        checkFormContact(jQuery('#form-contact'));
    });

    jQuery('#form-insurance').submit(function(e){
        e.preventDefault();
        checkFormInsurance(jQuery('#form-insurance'));
    });

    jQuery('#form-education').submit(function(e){
        e.preventDefault();
        checkFormEducation(jQuery('#form-education'));
    });

    jQuery('#form-request').submit(function(e){
        e.preventDefault();
        checkFormRequest(jQuery('#form-request'));
    });

    jQuery('#form-previouslicense').submit(function(e){
        e.preventDefault();
        checkFormPreviousLicense(jQuery('#form-previouslicense'));
    });

    jQuery('#form-preview').submit(function(e){
        e.preventDefault();
        checkFormPreview(jQuery('#form-preview'));
    });

    jQuery('.previous-form-btn').click(function(e){
        e.preventDefault();
        let current_form = $(this).data('current-form');
        console.log('current_form: ', current_form);
        switch(current_form){
            case 'form-preview':
                if(PREVIOUS_LICENSE_EXISTS) showFormPreviouslicense();  else  showFormRequest(); break;

            case 'form-previouslicense':
                showFormRequest(); break;

            case 'form-request':
                showFormEducation(); break;

            case 'form-education':
                showFormInsurance(); break;

            case 'form-insurance':
                showFormContact(); break;

            case 'form-contact':
                showFormPersonalInfo(); break;

            case 'form-personalinfo':
                if(PERSON_TYPE === 'legal'){
                    showFormLegalPerson();
                } else {
                    if(PREVIOUS_LICENSE_EXISTS) {
                        showFormPreviousLicenseUpload();
                    }
                }
                break;

            case 'form-legalperson':
                if(PREVIOUS_LICENSE_EXISTS) {
                    showFormPreviousLicenseUpload();
                }
                break;

            default:
                return;
        }
    });

    // ---------------------------------------------------------------------------------------------------

    // window.onbeforeunload = function() {
    //     swalError('در صورت بازنشانی صفحه کلیه اطلاعات شما پاک میشود');
    //     return '';
    // }
    // jQuery(window).on('beforeunload' , function(e){
    //     e.preventDefault();
    //     swalError('در صورت بازنشانی صفحه کلیه اطلاعات شما پاک میشود');
    //     return false;
    // });

    // ---------------------------------------------------------------------------------------------------

    DT_EDU = $('#education-table-form');

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '#new-education-record-btn', function () {
        let gradeId = $('#edu-grade option:selected').val();
        let gradeTitle = $('#edu-grade option:selected').text();
        let fieldId = $('#edu-field option:selected').val();
        let fieldTitle = $('#edu-field option:selected').text();
        let area = $('#edu-area').val();
        if( !gradeId ){
            swalError("فیلد مقطع تحصیلی انتخاب نشده است"); return false;
        }
        if( !fieldId ){
            swalError("فیلد رشته انتخاب نشده است"); return false;
        }
        if( !area ){
            swalError("فیلد گرایش نمی تواندخالی باشد"); return false;
        }
        let record = {gradeId: gradeId, gradeTitle: gradeTitle, fieldId: fieldId, fieldTitle: fieldTitle, area: area};
        addNewRecordToEducationTable(DT_EDU, record);
    } );

    // ---------------------------------------------------------------------------------------------------

    // ---------------------------------------------------------------------------------------------------

    DT_REQ = $('#request-table-form');

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '#new-request-basis-auth-record-btn', function () {
        let authId = $('#req-lic-auth option:selected').val();
        let authTitle = $('#req-lic-auth option:selected').text();
        let basisId = $('#req-lic-basis option:selected').val();
        let basisTitle = $('#req-lic-basis option:selected').text();
        if( ! authId ){
            swalError("فیلد صلاحیت پروانه انتخاب نشده است"); return false;
        }
        if( ! basisId ){
            swalError("فیلد پایه صلاحیت انتخاب نشده است"); return false;
        }
        let details = {authId: authId, authTitle:authTitle, basisId: basisId, basisTitle: basisTitle};
        addNewRecordToRequestBasisAuthTable(DT_REQ, details);
    } );

    // ---------------------------------------------------------------------------------------------------

    DT_PLIC = $('#previous-license-table-form');

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '#new-plic-basis-auth-record-btn', function () {
        let authId = $('#plic-auth option:selected').val();
        let authTitle = $('#plic-auth option:selected').text();
        let basisId = $('#plic-basis option:selected').val();
        let basisTitle = $('#plic-basis option:selected').text();
        let date = $('#plic-date').val();

        if( ! authId ){
            swalError("فیلد صلاحیت پروانه انتخاب نشده است"); return false;
        }

        if( ! basisId ){
            swalError("فیلد پایه صلاحیت انتخاب نشده است"); return false;
        }

        if( ! date ){
            swalError("فیلد تاریخ احراز صلاحیت انتخاب نشده است"); return false;
        }

        let details = {authId: authId, authTitle: authTitle, basisId: basisId, basisTitle:basisTitle, date: date};
        addNewRecordToPreviousLicenseBasisAuthTable(DT_PLIC, details);
    } );

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '.remove-education-record-btn', function () {
        // Get row number
        let row_number = $(this).closest('td').parent()[0].sectionRowIndex;
        let table = $('#education-table-form');
        let sessionStorageItem = 'dtEducationRecords';
        removeRowFromTable(table, row_number, sessionStorageItem);
    });

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '.remove-request-basis-auth-record-btn', function () {
        // Get row number
        let row_number = $(this).closest('td').parent()[0].sectionRowIndex;
        let table = $('#request-table-form');
        let sessionStorageItem = 'requestRecords';
        removeRowFromTable(table, row_number, sessionStorageItem);
    });

    // ---------------------------------------------------------------------------------------------------

    $(document).on('click', '.remove-plic-basis-auth-record-btn', function () {
        // Get row number
        let row_number = $(this).closest('td').parent()[0].sectionRowIndex;
        let table = $('#previous-license-table-form');
        let sessionStorageItem = 'pLicenseRecords';
        removeRowFromTable(table, row_number, sessionStorageItem);
    });

    // ---------------------------------------------------------------------------------------------------


    // This code will attach `fileselect` event to all file inputs on the page
    $(document).on('change', '#old-license-front', function() {
        var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1;
        if(numFiles > 1){
            swalError('فقط یک فایل انتخاب کنید');
            return false;
        }
        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // ---------------------------------------------------------------------------------------------------

    //below code executes on file input change and append name in text control
    $('#old-license-front').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text');
        input.val(label)
    });

    // ---------------------------------------------------------------------------------------------------

    // This code will attach `fileselect` event to all file inputs on the page
    $(document).on('change', '#old-license-rear', function() {
        var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1;
        if(numFiles > 1){
            swalError('فقط یک فایل انتخاب کنید');
            return false;
        }
        var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
    });

    // ---------------------------------------------------------------------------------------------------

    //below code executes on file input change and append name in text control
    $('#old-license-rear').on('fileselect', function(event, numFiles, label) {
        var input = $(this).parents('.input-group').find(':text');
        input.val(label);
    });

    // ---------------------------------------------------------------------------------------------------


    $(document).on('change', '#contact-province', function() {
        let province_id = $('#contact-province option:selected').val();
        let citiesSelector = $('#contact-city');
        UpdateCitiesComboBox(province_id, citiesSelector);

    });

    // ---------------------------------------------------------------------------------------------------

    $(document).on('change', '#edu-grade', function() {
        let edugrade = $('#edu-grade option:selected').val();
        let selector = $('#edu-field');
        UpdateEducationFieldComboBox(edugrade, selector);
        UpdateEducationAreaTextBox(edugrade);

    });

    // ---------------------------------------------------------------------------------------------------

    $(document).on('change', '.licensetypeselect', function() {
        let license_type = $(this).val();
        updateLicenseAuthenticationAndBasis(license_type);
    });

    // ---------------------------------------------------------------------------------------------------

    $(document).on('change', '#pi-transfer', function() {
        if($(this).is(':checked')) {
            showTransferField();
        } else {
            hideTransferField();
        }
    });

    // ---------------------------------------------------------------------------------------------------


});


/**
 *
 * Date picker Initiaize for each item having datepicker class
 *
 */
 $(".datepicker").persianDatepicker({
    altFormat: "YYYY/MM/DD",
    observer: true,
    autoClose: true,
    viewMode: "year",
    format: 'YYYY/MM/DD',
	initialValue: false,
    timePicker: {
        enabled: false
    },
});


/********************************************************************************
 *
 *
 * Functions
 *
 *
 *********************************************************************************/

/**
 * Clears any sesssion storage
 */
function clearSessionStorage() {
    sessionStorage.clear();
}

/**
 * Add a class to given step to show it as inactive step visually
 * @param {jqueryselector} itemClass
 */
 function inactiveStep(itemClass){
    jQuery('.'+ itemClass).addClass('inactive-step');
}

// ---------------------------------------------------------------------------------------------------

/**
 * Add a class to given step to show it as inactive step
 * @param {jqueryselector} itemClass
 */
function activeStep(itemClass){
    jQuery('.'+ itemClass).removeClass('inactive-step');
}

// ---------------------------------------------------------------------------------------------------


function showRegularPersonForm(){
    jQuery('#primary-info-formconform-btn').removeClass('hidden');
    jQuery('#regular-person-form').removeClass('hidden');
    jQuery('#legal-person-form').addClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function showLegalPersonForm(){
    jQuery('#primary-info-formconform-btn').removeClass('hidden');
    jQuery('#legal-person-form').removeClass('hidden');
    jQuery('#regular-person-form').addClass('hidden');
}

// ---------------------------------------------------------------------------------------------------


function setContactFormForRegularPerson(){
    jQuery('#contact-form-extra').removeClass('hidden');
    jQuery('#contact-form-title-for-regular-person').removeClass('hidden');
    jQuery('#contact-form-title-for-legal-person').addClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function setContactFormForLegalPerson(){
    jQuery('#contact-form-extra').addClass('hidden');
    jQuery('#contact-form-title-for-regular-person').addClass('hidden');
    jQuery('#contact-form-title-for-legal-person').removeClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

/**
 * This funcions show hidden form
 * Insuarance details form is hidden by default, so if applicant check "yes"
 * in this form the details form must be shown.
 */
function showInsuranceForm(){
    jQuery('#form-insurance-details').removeClass('hidden');
    jQuery("#insurance-instype").prop("required", true);
    jQuery("#insurance-insplace").prop("required", true);
}

// ---------------------------------------------------------------------------------------------------

/**
 * This funcions show shown form
 */
function hideInsuranceForm(){
    jQuery('#form-insurance-details').addClass('hidden');
    jQuery("#insurance-instype").prop("required", false);
    jQuery("#insurance-insplace").prop("required", false);
}

// ---------------------------------------------------------------------------------------------------

function prepareFormsBaseOnRequestType(request_type){

    // 4 = صدور برای اولین بار
    // 7 = پرداخت مابه تفاوت سال 1400 نسبت به 1399 (صدور برای اولین بار)
    // 9 = پرداخت مابه تفاوت سال 1401 نسبت به 1400 (صدور برای اولین بار)
    if(request_type == 4 || request_type == 7 || request_type == 9) {
        PREVIOUS_LICENSE_EXISTS = false;
    } else {
        PREVIOUS_LICENSE_EXISTS = true;
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
function checkFormConditions(form){
    let data = form.serializeArray();
    console.log(data);
    if(data.length === 2){
        activeIconStep2();
        showFormPrimaryInfo();
    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormPrimaryInfo(form){

    showLoading();
    console.log('form submitted');
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    if( data.hasOwnProperty('is_tranfered') && data.province12 == "" ) {
        swalError('انتخاب استان مبدا در صورتیکه عضو انتقالی هستید، اجباری می باشد.');
        hideLoading();
        return;
    }
    if(true){
        console.log(data);
        let _data = {
            persontype : data.persontype,
            birthdate : (data.persontype === 'regular')? data.birthdate1.toEnglishDigit(): data.birthdate2.toEnglishDigit(),
            requesttype : (data.persontype === 'regular')? data.requesttype1: data.requesttype2,
            licensetype : (data.persontype === 'regular')? data.licensetype1: data.licensetype2,
            morg : (data.persontype === 'regular')? data.morg1: data.morg2,
            studyfield : (data.persontype === 'regular')? data.studyfield1: data.studyfield2,
            province : (data.persontype === 'regular')? data.province1: data.province2,
            is_transfered: (data.hasOwnProperty('is_tranfered')) ? 1: 0,
            province_src : (data.persontype === 'regular')? data.province12: 0
        };

        // Add orgncode if person type = legal (حقیقی)
        if(data.persontype === 'legal'){
            _data.orgncode = data.orgncode,
            _data.orgregdate = data.orgregdate
        }

        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(_data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                } else if (res.code === 502) {
                    swalError(res.message);
                    return;
                }

                ShowToast(res.message, "success");
                // fill some fileds in next steps form
                fillFormPersonalInfo(res.data.personinfo);
                fillFormContact(res.data.contactinfo);
                fillFormRequest(PERSON_TYPE, res.data.membershipid);


                if (PERSON_TYPE === 'regular') { // person type = regular (حقیقی)                      
                    if(PREVIOUS_LICENSE_EXISTS){

                        showFormPreviousLicenseUpload();
                        fillFormPreviousLicense(res.data.employment_license_number);
                    } else {
                        showFormPersonalInfo();
                    }
                } else if (PERSON_TYPE === 'legal') { // person type = legal (حقوقی)
                    fillFormLegalPersonalInfo(res.data.legalpersoninfo);
                    if(PREVIOUS_LICENSE_EXISTS){
                        showFormPreviousLicenseUpload();
                        fillFormPreviousLicense(res.data.employment_license_number);
                    } else {
                        showFormLegalPerson();
                    }

                    if(res.data.legalpersoninfo._type === 'office') {
                        // hides some fields on legal form
                        hideSomeLegalPersonFields();
                    }


                } else { // person type not specified
                    swalError('نوع شخصی مشخص نیست');
                }

                // Make previous steps icons inactive 
                activeIconStep3();
                ManipulateRequestForm();
                SetRequestTypeHolder(); // Updates request type in some pages
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}
// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormLegalPerson(form){
     showLoading();
    let data = form.serializeArray();
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                ShowToast(res.message, "success");
                showFormPersonalInfo();
            },
            error: function (data){
                hideLoading();
                swalError('خطایی رخ داد');
            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormPersonalInfo(form){
     showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");
                showFormContact();
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });
    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormContact(form){
    showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    data['person_type'] = PERSON_TYPE;
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");
                showFormInsurance();
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');

            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
function checkFormInsurance(form){
    showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");
                showFormEducation();
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });
    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormEducation(form){
     showLoading();
    let data = {};
    // form.serializeArray().forEach(function(item){
    //     data[item.name] = item.value
    // });
    let dtEducationRecords = sessionStorage.getItem("dtEducationRecords");
    let records = (dtEducationRecords) ? JSON.parse(dtEducationRecords) : [];

    if(records.length === 0) {
        swalError('وارد کردن حداقل یک رکورد برای اطلاعات تحصیلی اجباری است');
    }
    data.educationRecords = records;
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");
                showFormRequest();
            },
            error: function (error){
                hideLoading();
                walError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });
    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید', 'باشه');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormRequest(form){
     showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    let dataRecords = sessionStorage.getItem("requestRecords");
    let records = (dataRecords) ? JSON.parse(dataRecords) : [];

    if(records.length === 0) {
        swalError('وارد کردن حداقل یک رکورد برای اطلاعات صلاحیت اجباری است');
    }
    data.requestRecords = records;

    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");

                if(PREVIOUS_LICENSE_EXISTS){
                    showFormPreviouslicense();
                } else {
                    showFormPreview();
                }
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید');
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
function checkFormPreviousLicense(form){
    showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value;

        // Transform date values from Persian numerical charaters to English numerical characters
        var dateFields = ["firstlicensedate", "lastrenewaldate", "expirationdate"];
        if(dateFields.includes(item.name)){
            data[item.name] = item.value.toEnglishDigit();
        }

    });
    let dataRecords = sessionStorage.getItem("pLicenseRecords");
    let records = (dataRecords) ? JSON.parse(dataRecords) : [];

    if(records.length === 0) {
        swalError('وارد کردن حداقل یک رکورد برای اطلاعات صلاحیت اجباری است');
    }

    // Transform date values from Persian numerical charaters to English numerical characters
    for(var i = 0; i < records.length; i++){
        records[i].date = records[i].date.toEnglishDigit();
    }
    // Attach records to data object to be sent
    data.records = records;

    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            data: JSON.stringify(data),
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                if(res.code === 400){
                    swalError(res.data.errors, res.message);
                    return;
                }
                ShowToast(res.message, "success");
                showFormPreview();
            },
            error: function (error){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید');
    }
}

// ---------------------------------------------------------------------------------------------------

function checkFormPreviousLicenseUploadFiles(){
    showLoading();
    let front = jQuery('#old-license-front').get(0).files[0];
    let rear = jQuery('#old-license-rear').get(0).files[0];
    var formData1 = new FormData();
    formData1.append("file",  front, 'front'); // previous license front page
    var formData2 = new FormData();
    formData2.append("file",  rear, 'rear'); // previous license rear page
    // ShowToast("در حال ارسال فایل", "success");

    uploadFile(formData1)
    .then(res => {
        console.log('front page uploaded');
        return uploadFile(formData2)
    })
    .then(res => {
        hideLoading();
        if(res.code === 400){
            swalError(res.data.errors, res.message);
            return;
        }
        ShowToast("فایل ها با موفقیت آپلود شدند", "success");
        if(PERSON_TYPE === 'legal') {
            // if person type is legal
            showFormLegalPerson();
        } else {
            // if person type is regular
            showFormPersonalInfo();
        }
    })
    .catch(error => {
        hideLoading();
        swalError('خطایی هنگام ارسال فایل های شما رخ داد. فایل هایتان را مجددا انتخاب و دوباره ارسال کنید');
    });

}

// ---------------------------------------------------------------------------------------------------

/**
 * Uploads file
 */
function uploadFile(formData){

    return new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "/application/previous-license-image-upload",
            success: function (data) {
                resolve(data);
            },
            error: function (error) {
                reject(error);
            },
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            async: true,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
        });
    });
}

// ---------------------------------------------------------------------------------------------------

/**
 * Triggers on form submit
 */
 function checkFormPreview(form){
    showLoading();
    if(true){
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            contentType: "application/json",
            headers: {
                'X-CSRF-TOKEN': CSRF
            },
            dataType: 'json',
            success: function (res) {
                hideLoading();
                let appid = res.data.appid;
                Swal.fire({
                    text: res.message,
                    showDenyButton: true,
                    confirmButtonText: 'پرداخت',
                    denyButtonText: 'بعدا پرداخت میکنم',
                  }).then((result) => {
                    let isConfirmed = result.hasOwnProperty('value') && result.value;
                    if (isConfirmed) {
                        window.location.href = "/application/" + appid + "/pay";
                    } else if (result.isDenied) {
                        window.location.href = "/application/list";
                    }
                })
            },
            error: function (data){
                hideLoading();
                swalError('خطایی رخ داده است. مجددا تلاش کنید.');
            }
        });

    } else {
        swalError('خطای اعتبار سنجی', 'لطفا کلیه فیلدها را پرکنید');
    }
}

// ---------------------------------------------------------------------------------------------------

function setPreviousForm(formClassName){
    PREVIOUS_STEP = formClass;
}

// ---------------------------------------------------------------------------------------------------

function getPreviousForm(formClassName){
    return PREVIOUS_STEP;
}

// ---------------------------------------------------------------------------------------------------

function checkPreviousForm(){
    if(getPreviousForm() === null){
        jQuery('.previous-form-btn').addClass('hidden');
    } else {
        jQuery('.previous-form-btn').removeClass('hidden');
    }
}

// ---------------------------------------------------------------------------------------------------

function showTransferField(){
    jQuery('#transfer-field').removeClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function hideTransferField(){
    jQuery('#transfer-field').addClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function showAnjomanMembershipIdField(){
    jQuery('#req-anjoman-membership-id-field').removeClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function hideAnjomanMembershipIdField(){
    jQuery('#req-anjoman-membership-id-field').addClass('hidden');
}

// ---------------------------------------------------------------------------------------------------

function showFormPrimaryInfo(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-primary-info').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormPreviousLicenseUpload(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-previouslicense-upload').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormLegalPerson(){

    if(PREVIOUS_LICENSE_EXISTS){
        // this conditions controls whther to show previous step button
        jQuery("#previous-form-btn-legalperson").removeClass('hidden');
    }

    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-legalperson').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormPersonalInfo(){
    if ((PERSON_TYPE === 'legal') || PREVIOUS_LICENSE_EXISTS){
        // this conditions controls whther to show previous step button
        jQuery("#previous-form-btn-personalinfo").removeClass('hidden');
    }
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-personalinfo').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormContact(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-contact').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormInsurance(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-insurance').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormEducation(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-education').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormRequest(){
    let submitBtnText = (PREVIOUS_LICENSE_EXISTS) ? 'بعدی' : 'مشاهده پیش نمایش';
    jQuery('#request-form-submit-btn').text(submitBtnText);
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-request').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormPreviouslicense(){
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-previouslicense').removeClass('inactive-form');
}

// ---------------------------------------------------------------------------------------------------

function showFormPreview(){
    getPreview();
    jQuery('.app-form').addClass('inactive-form');
    jQuery('.app-form-preview').removeClass('inactive-form');
    // if(xxxxx === 'regular') {

    // } else {

    // }
    // setPreviousForm('app-form-previouslicense');
}

// ---------------------------------------------------------------------------------------------------

function UpdateCitiesComboBox(province_id, selector, selected_option = null){
    showLoading();
    $.ajax({
        type: "GET",
        url: "/application/cities?province_id=" + province_id,
        success: function (res) {
            let cities = res.data.cities;
            let options = '';
            cities.forEach(function(city){
                if(selected_option && selected_option == city.city_id){
                    options += '<option value="' + city.city_id + '" selected="selected">' + city.city_title + '</options>';
                } else {
                    options += '<option value="' + city.city_id + '">' + city.city_title + '</options>';
                }
            });
            selector.append(options);
            hideLoading();
        },
        error: function (error) {
            hideLoading();
            swalError('خطا در دریافت لیست شهرها. دوباره تلاش کنید');
        },
        headers: {
            'X-CSRF-TOKEN': CSRF
        },
        async: true,
        cache: false,
        contentType: false,
        processData: false,
    });
}

// ---------------------------------------------------------------------------------------------------

function UpdateEducationFieldComboBox(edugrade, selector) {

    showLoading();
    $.ajax({
        type: "GET",
        url: "/application/study-fields?grade_id=" + edugrade,
        success: function (res) {
            let fields = res.data.study_fields;
            let options = '';
            fields.forEach(function(field){
                options += '<option value="' + field.study_field_id + '">' + field.study_field_title + '</options>';
            });
            selector.find('option').remove();
            selector.append(options);
            hideLoading();
        },
        error: function (error) {
            hideLoading();
            swalError('خطا در دریافت لیست رشته ها. دوباره تلاش کنید');
        },
        headers: {
            'X-CSRF-TOKEN': CSRF
        },
        async: true,
        cache: false,
        contentType: false,
        processData: false,
    });
}

// ---------------------------------------------------------------------------------------------------

function UpdateEducationAreaTextBox(edugrade){
    if(edugrade == 7 || edugrade == 8) {
        jQuery('#edu-area').val('سایر');
    }
}

// ---------------------------------------------------------------------------------------------------

function updateLicenseAuthenticationAndBasis(license_type_id) {
    showLoading();
    console.log()
    $.ajax({
        type: "GET",
        url: "/application/license-basis-auth-options/" + license_type_id,
        success: function (res) {
            var options_html = '<option value="">انتخاب کنید</option>';
            // prepare and append authentication options
            let auth_options = res.data.auth_options;
            auth_options.forEach(function(field){
                options_html += '<option value="' + field.lic_auth_id + '">' + field.lic_auth_title + '</options>';
            });
            $('#req-lic-auth').html(options_html);
            $('#plic-auth').html(options_html);

            // prepare and append basis options
            options_html = '<option value="">انتخاب کنید</option>';
            let basis_options = res.data.basis_options;
            if(basis_options.length > 0) {
                if(basis_options.length > 1) {
                    basis_options.forEach(function(field){
                        options_html += '<option value="' + field.lic_basis_id + '">' + field.lic_basis_title + '</options>';
                    });
                }else {
                    options_html += '<option value="' + basis_options[0].lic_basis_id + '" selected>' + basis_options[0].lic_basis_title + '</options>';
                    $('#req-lic-basis').hide();
                    $('#plic-basis').hide();;
                }
            }
            $('#req-lic-basis').html(options_html);
            $('#plic-basis').html(options_html);;

            hideLoading();
        },
        error: function (error) {
            hideLoading();
            swalError('خطا در دریافت لیست صلاحیت ها و پایه ها بر اساس نوع پروانه. دوباره تلاش کنید');
        },
        headers: {
            'X-CSRF-TOKEN': CSRF
        },
        async: true,
        cache: false,
        contentType: false,
        processData: false,
    });
}

// ---------------------------------------------------------------------------------------------------

function ManipulateRequestForm(){

    var mrud_org_id, license_type_id;
    if (PERSON_TYPE === 'regular') {
        mrud_org_id = $('#pi-morg1 option:selected').val();
        license_type_id = $('#pi-licensetype1 option:selected').val();

    } else {
        mrud_org_id = $('#pi-morg2 option:selected').val();
        license_type_id = $('#pi-licensetype2 option:selected').val();
    }

    // Decide about membership number filed label text
    license_type_id = parseInt(license_type_id);
    if(license_type_id == 205) {
        $('#req-membership-id-field-label').text('شماره عضویت مسئول دفتر');
    } else {
        $('#req-membership-id-field-label').text('شماره عضویت');
    }

    // Decide to show anjoman memebershiop number or not to show
    mrud_org_id = parseInt(mrud_org_id);

    const org_list = [3, 10, 6, 4, 5, 12, 7, 11];
    if(org_list.includes(mrud_org_id)) {
        showAnjomanMembershipIdField();
    } else {
        hideAnjomanMembershipIdField();
    }
}

// ---------------------------------------------------------------------------------------------------

/**
 * Fills Personal Info form via fiven information
 */
function fillFormPersonalInfo(personInfo) {
    $("#persinfo-name").val(personInfo.name);
    $("#persinfo-lname").val(personInfo.family);
    $("#persinfo-ncode").val(fixNationalCodeNumber(personInfo.nin));
}

// ---------------------------------------------------------------------------------------------------

function fillFormLegalPersonalInfo(legalpersonInfo) {
    $("#legalperson-orgname").val(legalpersonInfo.Name);
    $("#legalperson-ncode").val(legalpersonInfo.NationalCode);
    $("#legalperson-regnum").val(legalpersonInfo.RegisterNumber);
    $("#legalperson-regdate").val(legalpersonInfo.RegisterDate);
    $("#legalperson-establishdate").val(legalpersonInfo.EstablishmentDate);
}

// ---------------------------------------------------------------------------------------------------

function fillFormContact(contactinfo) {
    $("#contact-mobile").val(contactinfo.mobile);
}

// ---------------------------------------------------------------------------------------------------

function fillFormRequest(persontype, membershipid) {
    var orgTitle, provinceTitle, reqtype, licensetype;
    if(persontype === 'regular'){
        orgTitle = jQuery("#pi-morg1 option:selected").text();
        provinceTitle = jQuery("#pi-province1 option:selected").text();
        reqtype = jQuery("#pi-requesttype1 option:selected").text();
        licensetype = jQuery("#pi-licensetype1 option:selected").text();

    } else {
        orgTitle = jQuery("#pi-morg2 option:selected").text();
        provinceTitle = jQuery("#pi-province2 option:selected").text();
        reqtype = jQuery("#pi-requesttype2 option:selected").text();
        licensetype = jQuery("#pi-licensetype2 option:selected").text();
    }

    $("#req-orgname").val(orgTitle);
    $("#req-province").val(provinceTitle);
    $("#req-licensetype").val(licensetype);
    $("#req-retype").val(reqtype);
    $("#req-membership-id").val(membershipid); // ؛Update member ship id anyway
    MEMBERSHIP_ID = membershipid;



}

// ---------------------------------------------------------------------------------------------------

function fillFormPreviousLicense(employment_license_number)
{
    // This condition is essential to chekc if this filed is filled by function _fillFormWithUserHistory
    if($("#plic-licenseno").val().length === 0){
        $("#plic-licenseno").val(employment_license_number);
        EMPLOYMENT_LICENSE_NUMBER = employment_license_number;
    }
}

// ---------------------------------------------------------------------------------------------------

function addNewRecordToEducationTable(table, details){
    let sessionStorageItemName = "dtEducationRecords";
    let record = {grade: details.gradeId, field: details.fieldId, area: details.area};
    // Get previous items if exists from session storage
    var st = sessionStorage.getItem(sessionStorageItemName);
    var requestRecords = (st) ? JSON.parse(st) : [];
    // Calculate index of next row
    let index = requestRecords.length;
    // Calculate roww number to show
    let row_number = index + 1;
    // create a row html
    let row = '<tr><td>'+row_number+'</td><td>'+details.gradeTitle+'</td><td>'+details.fieldTitle+'</td><td>'+details.area+'</td><td><button type="button" class="btn btn-default btn-sm remove-education-record-btn" >حذف</button></td></tr>';
    // append to table
    table.append(row);
    // add to recorfs array
    requestRecords[index] = record;
    // save array to session storage
    sessionStorage.setItem(sessionStorageItemName, JSON.stringify(requestRecords));
}

// ---------------------------------------------------------------------------------------------------

function addNewRecordToRequestBasisAuthTable(table, details){
    let sessionStorageItemName = "requestRecords";
    let record = {auth: details.authId, basis: details.basisId};
    // Get previous items if exists from session storage
    var st = sessionStorage.getItem(sessionStorageItemName);
    var requestRecords = (st) ? JSON.parse(st) : [];
    // Calculate index of next row
    let index = requestRecords.length;
    // Calculate roww number to show
    let row_number = index + 1;
    // create a row html
    let row = '<tr><td>'+row_number+'</td><td>'+details.authTitle+'</td><td>'+details.basisTitle+'</td><td><button type="button" class="btn btn-default btn-sm remove-request-basis-auth-record-btn" >حذف</button></td></tr>';
    // append to table
    table.append(row);
    // add to recorfs array
    requestRecords[index] = record;
    // save array to session storage
    sessionStorage.setItem(sessionStorageItemName, JSON.stringify(requestRecords));
}

// ---------------------------------------------------------------------------------------------------

function addNewRecordToPreviousLicenseBasisAuthTable(table, details){
    let sessionStorageItemName = "pLicenseRecords";
    let record = {auth: details.authId, basis: details.basisId, date: details.date};

    // Get previous items if exists from session storage
    var st = sessionStorage.getItem(sessionStorageItemName);
    var requestRecords = (st) ? JSON.parse(st) : [];
    // Calculate index of next row
    let index = requestRecords.length;
    // Calculate roww number to show
    let row_number = index + 1;
    // create a row html
    let row = '<tr><td>'+row_number+'</td><td>'+details.authTitle+'</td><td>'+details.basisTitle+'</td><td>'+details.date+'</td><td><button type="button" class="btn btn-default btn-sm remove-plic-basis-auth-record-btn" >حذف</button></td></tr>';
    // append to table
    table.append(row);
    // add to recorfs array
    requestRecords[index] = record;
    // save array to session storage
    sessionStorage.setItem(sessionStorageItemName, JSON.stringify(requestRecords));
}

// ---------------------------------------------------------------------------------------------------

function removeRowFromTable(table_selector, row_number, sessionStorageItemName){
    table_selector.find("tbody").find("tr").eq(row_number).remove();
    // Get previous items if exists from session storage
    var st = sessionStorage.getItem(sessionStorageItemName);
    var records = (st) ? JSON.parse(st) : [];
    // Calculate index of item in rray
    let index = row_number - 1;
    // Remove item from array
    records.splice(index,1);
    // Store new array in session storage
    sessionStorage.setItem(sessionStorageItemName, JSON.stringify(records));
    // update row numbers in table
    updateTableRowNumber(table_selector);
}

// ---------------------------------------------------------------------------------------------------

function updateTableRowNumber(table_selector){
    let rows = table_selector.find("tbody").find("tr");
    for(var i = 1; i < rows.length; i++){
        $(rows[i]).find('td').eq(0).text(i);
    }
}

// ---------------------------------------------------------------------------------------------------

function getPreview(){
    showLoading();
    $.ajax({
        type: "GET",
        url: '/application/get-preview',
        success: function (res) {
            fillPreviewFields(res.data);
            hideLoading();
        },
        error: function (error) {
            hideLoading();
            swalError('خطا در دریافت اطلاعات پیش نمایش. دوباره تلاش کنید.');
        },
        headers: {
            'X-CSRF-TOKEN': CSRF
        },
        async: true,
        cache: false,
        contentType: false,
        processData: false,
    });
}

function fillPreviewFields(data){
        var counter = 1;

        // Legal Person
        if(data.legalperson){
            let lp = data.legalperson;
            $("#preview-legalperson-orgname").val(lp.org_name);
            $("#preview-legalperson-ncode").val(lp.org_ncode);
            $("#preview-legalperson-regnum").val(lp.org_reg_number);
            $("#preview-legalperson-regdate").val(lp.org_reg_date);
            $("#preview-legalperson-establishdate").val(lp.org_establishment_date);
            $("#preview-org-info").removeClass('hidden');
        }

        // regular person
        let rp = data.personal_info;
        $("#preview-persinfo-name").val(rp.p_name);
        $("#preview-persinfo-lname").val(rp.p_lname);
        $("#preview-persinfo-ncode").val(rp.p_ncode);
        $("#preview-persinfo-birthplace").val(rp.province_title);
        if(rp.p_marriage_status_id == 0){
            $("#preview-persinfo-radio-marital-single").prop("checked", true);
        } else {
            $("#preview-persinfo-radio-marital-married").prop("checked", true);
        }

        // contact
        let con = data.contact_info;
        $("#preview-contact-mobile").val(con.mobile);
        $("#preview-contact-email").val(con.email);
        $("#preview-contact-tel").val(con.tel_number);
        $("#preview-contact-tel-code").val(con.tel_zone);
        $("#preview-contact-postalcode").val(con.postal_code);
        $("#preview-contact-province").val(con.province_title);
        $("#preview-contact-city").val(con.city_title);
        $("#preview-contact-street1").val(con.street1);
        $("#preview-contact-street2").val(con.street2);
        $("#preview-contact-no").val(con.no);
        $("#preview-contact-floor").val(con.floor);
        $("#preview-contact-unit").val(con.unit);

        // insurance
        let ins = data.insurance_info;
        $("#preview-insurance-instype").val(ins.ins_type);
        // $("#preview-insurance-insplace").val(ins.ins_pay_location);
        $("#preview-insurance-insoccupation").val(ins.ins_main_occupation);
        if(ins.ins_available == 1){
            $("#preview-radio-insurance-yes").prop("checked", true);
        } else {
            $("#preview-radio-insurance-no").prop("checked", true);
        }

        // education records
        let education_records = data.education_records;
        education_records.forEach(function(item){
            let row = '<tr><td>'  + counter
                    + '</td><td>' + item.education_grade_title
                    + '</td><td>' + item.study_field_title
                    + '</td><td>' + item.edu_area + '</td></tr>';
            $("#preview-education-table > tbody").append(row);
            counter++;
        });

        // request
        let req = data.request;
        $("#preview-req-orgname").val(req.morg_title);
        $("#preview-req-province").val(req.province_title);
        $("#preview-req-licensetype").val(req.license_type_title);
        $("#preview-req-retype").val(req.req_type_title);
        $("#preview-req-membership-id").val(req.req_membership_no);

        // request records
        counter = 1;
        let request_records = data.request_records;
        request_records.forEach(function(item){
            let row = '<tr><td>'  + counter
                    + '</td><td>' + item.lic_auth_title
                    + '</td><td>' + item.lic_basis_title
                    + '</td><td></tr>';
            $("#preview-request-table-form > tbody").append(row);
            counter++;
        });

        // previous license
        if(data.previous_license){
            let plic = data.previous_license;
            $("#preview-plic-licenseno").val(plic.plic_no);
            $("#preview-plic-licenseserialno").val(plic.plic_serial_no);
            $("#preview-plic-firstlicensedate").val(plic.plic_date_first_issue);
            $("#preview-plic-lastrenewaldate").val(plic.plic_date_last_renewal);
            $("#preview-plic-expirationdate").val(plic.plic_date_expire);
            $('#preview-previous-license').removeClass('hidden');
        }

        // previous license records
        if(data.previous_license_records){
            counter = 1;
            let plic_records = data.previous_license_records;
            plic_records.forEach(function(item){
                let row = '<tr><td>'  + counter
                        + '</td><td>' + item.lic_auth_title
                        + '</td><td>' + item.lic_basis_title
                        + '</td><td>' + item.plic_auth_date
                        + '</td><td></tr>';
                $("#preview-previous-license-table > tbody").append(row);
                counter++;
            });
        }


        // previous license images
        if(data.previous_license_images){
            let plic_images = data.previous_license_images;
            $("#preview-image-front").attr('src', plic_images.front);
            $("#preview-image-rear").attr('src', plic_images.rear);
            $("#preview-previous-license-upload").removeClass("hidden");

        }
}

// ------------------------------------------------------------------------------

function ajax_data(data =[],method='GET',url='')
{
    var tmp = null;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        async: false,
        global: false,
        dataType: 'json',
        url:url,
        method:method,
        data:data,
        success:function(response)
        {
            tmp = response;
        }
    });
    return tmp;
}

/**
 * Updates a label in each item having class request-type-holder.
 * This label shows request type in some pages.
 */
function SetRequestTypeHolder(){
    var reqtype;
    let p,r;
    if(PERSON_TYPE == 'regular') {
        p = 1;
        r = jQuery("#pi-requesttype1").val();
        reqtype = jQuery("#pi-requesttype1 option:selected").text();
    } else {
        p = 2;
        r = jQuery("#pi-requesttype2").val();
        reqtype = jQuery("#pi-requesttype2 option:selected").text();
    }

    let res = ajax_data({type_id: r,p_id: p},'POST',siteurl+ '/application/cost');
    let num = res.cost;
    // Update license type title in all pages
    $('.request-type-holder').text(reqtype);
    num = num.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,")
    $('.request-cost-holder').text(num + ' ریال ');
}

// ------------------------------------------------------------------------------

function fillFormWithUserHistory()
{
    $.ajax({
        type: "GET",
        url: '/application/lastest-application-data',
        headers: {
            'X-CSRF-TOKEN': CSRF
        },
        async: true,
        cache: false,
        contentType: false,
        processData: false,
        error: function (error) {
            hideLoading();
        },
        success: function (res) {
            hideLoading();
            _fillFormWithUserHistory(res.data);
        }
    });
}

function hideSomeLegalPersonFields(){
    $('#legalperson-orgname-div').hide();
    $('#legalperson-ncode-div').hide();
    $('#legalperson-establishdate-div').hide();
}

function _fillFormWithUserHistory(data){
    // fill primary form
    let app = data.application;
    if(app) {
        $('#pi-requesttype1 option[value="'+app.app_type_id+'"]').prop('selected', true);
        $('#pi-requesttype2 option[value="'+app.app_type_id+'"]').prop('selected', true);
        $('#pi-morg1 option[value="'+app.mrud_org_id+'"]').prop('selected', true);
        $('#pi-morg2 option[value="'+app.mrud_org_id+'"]').prop('selected', true);
        $('#pi-province1 option[value="'+app.province_id+'"]').prop('selected', true);
        $('#pi-province2 option[value="'+app.province_id+'"]').prop('selected', true);
        $('#pi-licensetype1 option[value="'+app.license_type_id+'"]').prop('selected', true);
        $('#pi-licensetype2 option[value="'+app.license_type_id+'"]').prop('selected', true);
        $('#pi-studyfield1 option[value="'+app.edu_field_id+'"]').prop('selected', true);
        $('#pi-studyfield2 option[value="'+app.edu_field_id+'"]').prop('selected', true);
        $('#pi-orgncode').val(app.user_org_ncode);
        if(app.applicant_type_id == 1) {
            $('#pi-birthdate1').val(app.user_birthdate);
            $('#regular-person').click();
            // $('#office-person').click();
            $('#regular-person').parent().parent().removeClass('hidden');
            $('#office-person').parent().parent().removeClass('hidden');
            showRegularPersonForm();
        }else {
            $('#pi-birthdate2').val(app.user_birthdate);
            $('#legal-person').click();
            $('#legal-person').parent().parent().removeClass('hidden');
            showLegalPersonForm();
        }
        // Decide to show which forms (previous license deails and uload forms must not be shown for reqest type of 'صدرو برای اولین بار' )
        prepareFormsBaseOnRequestType(app.app_type_id);
        updateLicenseAuthenticationAndBasis(app.license_type_id);
    }

    // Fill Personal Info form
    let person = data.person;
    if(person){
        $('#persinfo-birthplace option[value="'+person.p_birth_location+'"]').prop('selected', true);
        if(person.app_type_id) {
            $('#persinfo-radio-marital-married').prop('checked', true);
        } else {
            $('#persinfo-radio-marital-single').prop('checked', true);
        }
    }

    // Fill Contact Info form
    let contact = data.contact;
    if(contact) {
        $('#contact-email').val(contact.email);
        $('#contact-tel').val(contact.tel_number);
        $('#contact-telcode').val(contact.tel_zone);
        $('#contact-postalcode').val(contact.postal_code);
        $('#contact-province option[value="'+contact.province_id+'"]').prop('selected', true);
        $('#contact-city option[value="'+contact.city_id+'"]').prop('selected', true);
        $('#contact-street1').val(contact.street1);
        $('#contact-street2').val(contact.street2);
        $('#contact-no').val(contact.no);
        $('#contact-floor').val(contact.floor);
        $('#contact-unit').val(contact.unit);
        $('#contact-work-address').val(contact.work_address);
        $('#contact-work-tel-number').val(contact.work_tel_number);
    }

    // Fill Insurance Info from
    let ins = data.insurance;
    if(ins) {
        if(ins.ins_available) {
            showInsuranceForm();
            $('#radio-insurance-yes').prop('checked', true);
            $('#insurance-instype').val(ins.ins_type);
            $('#insurance-insplace').val(ins.ins_pay_location);
            $('#insurance-insoccupation').val(ins.ins_main_occupation);
        } else {
            $('#radio-insurance-no').prop('checked', true);
        }
    }

    // Fill Education info form
    let edu_records = data.education_records;
    if (edu_records) {
        edu_records.forEach(function(item){
            let details = {gradeId: item.edu_grade_id, gradeTitle: item.education_grade_title, fieldId: item.edu_field_id, fieldTitle: item.study_field_title, area: item.edu_area};
            addNewRecordToEducationTable(DT_EDU, details);
        });
    }

    // Fill Request Form
    let req = data.request;
    if(req) {
        $('#req-anjoman-membership-id').val(req.req_anjoman_membership_no);
    }

    // Fill request records form
    // let req_records = data.request_records;
    // if (req_records) {
    //     req_records.forEach(function(item){
    //         let details = {authId: item.license_auth_id, authTitle: item.lic_auth_title, basisId: item.license_basis_id, basisTitle: item.lic_basis_title};
    //         addNewRecordToRequestBasisAuthTable(DT_REQ, details);
    //     });        
    // }

    // Fill Previous license info 
    let plic = data.previous_license;
    if(plic) {
        // $('#plic-licenseno').val(plic.plic_no);
        $('#plic-licenseserialno').val(plic.plic_serial_no);
        $('#plic-firstlicensedate').val(plic.plic_date_first_issue);
        $('#plic-lastrenewaldate').val(plic.plic_date_last_renewal);
        $('#plic-expirationdat').val(plic.plic_date_expire);
    }
    // Fill Previous license records
    let plic_records = data.previous_license_records;
    if (plic_records) {
        plic_records.forEach(function(item){
            let details = {authId: item.lic_auth_id, authTitle: item.lic_auth_title, basisId: item.lic_basis_id, basisTitle: item.lic_basis_title, date: item.plic_auth_date};
            addNewRecordToPreviousLicenseBasisAuthTable(DT_PLIC, details);
        });
    }


    // Finally we update city base on province id
    let citiesSelector = $('#contact-city');
    console.log(contact);
    if(contact !== null) {
        UpdateCitiesComboBox(contact.province_id, citiesSelector ,contact.city_id);
    }
}


function preventUserToCleanMembershipId(){
    let selector = jQuery('#req-membership-id');
    let current_value = selector.val();
    let fixed_part = getMembershipIdFixedPart();
    if( current_value.search(fixed_part) == -1){
        selector.val(fixed_part + 'XXXXX');
        swalError("در قسمت شماره عضویت فقط قسمت XXXXX را با عددجایگذاری کنید.");
    }
}

/**
 * Calculates fixed part of membershipid string
 * @returns string
 */
function getMembershipIdFixedPart(){
    let parts = MEMBERSHIP_ID.split('-');
    let parts_count = parts.length;
    var fixed_part = "";
    for(var i = 0; i <  parts_count - 1; i++){
        fixed_part += parts[i] + "-";
    }
    return fixed_part;
}

function preventUserToCleanEmploymentLicenseNumber(){
    let selector = jQuery('#plic-licenseno');
    let current_value = selector.val();
    let fixed_part = getEmploymentLicenseNumberFixedPart();
    if( current_value.search(fixed_part) == -1){
        selector.val(fixed_part + 'XXXXX');
        swalError("در قسمت شماره پروانه اشتغال به کار فقط قسمت XXXXX را با عددجایگذاری کنید.");
    }
}

/**
 * Calculates fixed part of employment license number string
 * @returns string
 */
function getEmploymentLicenseNumberFixedPart(){
    let parts = EMPLOYMENT_LICENSE_NUMBER.split('-');
    let parts_count = parts.length;
    var fixed_part = "";
    for(var i = 0; i <  parts_count - 1; i++){
        fixed_part += parts[i] + "-";
    }
    return fixed_part;
}
