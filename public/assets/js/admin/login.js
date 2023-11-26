var CSRF = $('meta[name="csrf-token"]').attr('content');
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
                reloadCaptcha();  
                return;
            }  
            if(res.code === 401 || res.code === 403){
                swalError(res.message); 
                reloadCaptcha();  
                return;
            }          
            window.location.href = res.redirect;            
        },
        error: function (error){
            reloadCaptcha();
            hideLoading();    
            swalError('خطایی رخ داده است. مجددا تلاش کنید.');         
        }
    });

}


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

function reloadCaptcha()
{
    let currentDate = new Date(); 
    let timestamp = currentDate. getTime();
    let captcha = '/captcha?time=' + timestamp;
    $('#captcha-img').attr('src', captcha);
}


jQuery(document).ready(function(){

    jQuery(document).on('submit', '#login-form',function(e){
        e.preventDefault();
        let form = $('#login-form');
        let data = form.serializeObject() 
        let url =  form.attr('action');
        postLogin(url, data);
    });

    jQuery('#reload-captcha').click(function(){
        reloadCaptcha();
    });
});
