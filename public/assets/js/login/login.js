var CSRF = $('meta[name="csrf-token"]').attr('content');
var MOBILE, NCODE, CAPTCHA;


/**
 * Show login form
 */
function showLoginForm()
{
    jQuery('#login-step-1').removeClass('hidden');
    jQuery('#login-step-2').addClass('hidden');
}

/**
 * Shows Otp form
 */
function showOtpForm()
{
    startTimer();
    jQuery('#login-step-1').addClass('hidden');
    jQuery('#login-step-2').removeClass('hidden');
}

/**
 * Posts login form
 */
function postLogin(url, data)
{
    showLoading();
    jQuery.ajax({
        url: url,
        type: 'post',
        data: data,
        headers: {
            'X-CSRF-TOKEN': CSRF  //for object property name, use quoted notation shown in second
        },
        dataType: 'json',
        success: function (res) {
            hideLoading();
            if(res.code === 400){
                swalError(res.data.errors, res.message);   
                return;
            } else if (res.code === 409) {
                // Conflict error for moblie
                Swal.fire({
                    text: res.message,
                    confirmButtonText: 'تایید',
                    showCancelButton: true,
                    cancelButtonText: 'ویرایش',
                }).then((result) => {

                    let isConfirmed = result.hasOwnProperty('value') && result.value;
                    if (isConfirmed) {
                        data.login_with_new_number=1;
                        data.login_secret = res.data.login_secret;
                        postLogin(url, data);
                    } else {
                        // User denied to login with new number and wants to login with previous number, so showlogin page again
                        reloadCaptcha();
                        showLoginForm();
                    }
                });
                return;
            }            
            
            setOtpSentMobile(res.data.mobile);
            showOtpForm();
            reloadCaptcha();
        },
        error: function (error){
            reloadCaptcha();
            hideLoading();    
            swalError('خطایی رخ داده است. مجددا تلاش کنید.');         
        }
    });

}

/**
 * Posts otp form
 */
function postOtp(url, data)
{
    showLoading();    
    jQuery.ajax({
        url: url,
        type: 'post',
        data: data,
        headers: {
            'X-CSRF-TOKEN': CSRF  //for object property name, use quoted notation shown in second
        },
        dataType: 'json',
        success: function (res) {
            hideLoading();
            if(res.code === 400){
                // Validation Error  
                swalError(res.data.errors, res.message);   
                return;
            }
            window.location.href = res.redirect;
        },
        error: function (error){             
            hideLoading();     
            swalError('خطایی رخ داده است. مجددا تلاش کنید.');       
        }
    });

}

function setOtpSentMobile(mobile){
    $('#entered-mobile').text(mobile);
}

var TIMER, TIMER_RULE, MY_INTERVAL;

jQuery(document).ready(function(){

    TIMER_RULE = parseInt($('#timer').text());

    jQuery(document).on('submit', '#login-form',function(e){
        e.preventDefault();
        let form = $('#login-form');
        let data = form.serializeObject() 
        let url =  form.attr('action');
        postLogin(url, data);
    });

    jQuery(document).on('submit', '#otp-form',function(e){
        e.preventDefault();
        let form = $('#otp-form');
        let data = form.serialize();        
        let url =  form.attr('action');  
        postOtp(url, data);
    });
        
    jQuery('#change-mobile-btn').click(function(e){
        reloadCaptcha();
        stopTimer();
        showLoginForm();
    });

    jQuery('#resend-otp-btn').click(function(e){
        
        if(TIMER === 0){
            // let form = $('#login-form');
            // let data = form.serializeObject() 
            // let url =  form.attr('action');
            // postLogin(url, data);
            reloadCaptcha();
            stopTimer();
            showLoginForm();
        } else {
            swalError('لطفا تا اتمام زمان منتظر باشید.');
        }
    });    

    jQuery('#reload-captcha').click(function(){
        reloadCaptcha();
    });
});


$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function startTimer(){
    TIMER = TIMER_RULE;
    MY_INTERVAL = setInterval(function(){
        TIMER--;
        $('#timer').text(TIMER);
        if(TIMER === 0) {
            stopTimer();
        }
    }, 1000);
}

function stopTimer(){
    clearInterval(MY_INTERVAL);
};

function reloadCaptcha()
{
    let currentDate = new Date(); 
    let timestamp = currentDate. getTime();
    let captcha = '/captcha?time=' + timestamp;
    $('#captcha-img').attr('src', captcha);
}


$(document).ready(function(){
    // swalSuccess('در حال حاضر امکان ثبت درخواست فقط برای اشخاص حقیقی امکان پذیر است.' , 'پیام مدیر سیستم', 'فهمیدم');
});