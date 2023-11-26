$(document).ready(function(){

    activeIconStep4();

    $('.form-request-pay').submit(function(e){
        e.preventDefault();
        let form = $(this);
        requestPayment(form);
    });

    jQuery('#payments-step-form').submit(function(e){
        e.preventDefault();
        SubmitPaymentStep(jQuery('#payments-step-form'));
    });

});


function requestPayment(form){
    showLoading();
    let data = {};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });
    jQuery.ajax({
        url: form.attr('action'),
        type: 'post',
        contentType: "application/json",
        data: JSON.stringify(data),
        headers: {
            'X-CSRF-TOKEN': CSRF  //for object property name, use quoted notation shown in second
        },
        dataType: 'json',
        success: function (res) {
            hideLoading();
            if(res.code === 400){
                swalError(res.data.errors, res.message);
                return;
            }
            if(res.data.pay_request.status){
                let transaction_id = res.data.pay_request.data.transaction_id;
                let sign = res.data.pay_request.data.sign;
                FillBankForm(transaction_id, sign)
            } else {
                swalError(res.data.pay_request.message);
            }


        },
        error: function (error){
            hideLoading();
            swalError('خطایی رخ داده است. مجددا تلاش کنید.');
        }
    });
}

function FillBankForm(transaction_id, sign){
    $('#transaction_id').val(transaction_id);
    $('#sign').val(sign);
    SubmitBankForm();
}

function SubmitBankForm(){var date = new Date();
    let hour = (date.getHours().length == 1) ? '0'+date.getHours() : date.getHours();
    let minute = (date.getMinutes().length == 1) ? '0'+date.getMinutes() : date.getMinutes();

    if((hour == '00' && minute < 30) || (hour == 23 && minute >= 30)) {
        swalError('کاربر گرامی به دلیل بروز خطای پرداخت در ساعات 23:30 الی 00:30 امکان پرداخت وجود ندارد لطفا در ساعات دیگر نسبت به هرگونه پرداخت اقدام نمایید.\n' +
            'توجه فرمایید ادامه ی فرایند پرداخت بعد از این ساعت در کارتابل سابقه درخواست ها در دسترسی می باشد.');
    }else {
        console.log('submitted');
        $('#bank-forward-form').submit();
    }
}

function SubmitPaymentStep(form) {

    let data ={};
    form.serializeArray().forEach(function(item){
        data[item.name] = item.value
    });

    console.log(data);

    if(data.all_payments_done == 1){
        window.location.href = '/application/' + data.application_id + '/tracking-code';
    } else {
        swalError('خطای اعتبار سنجی', 'نسبت به پرداخت اقدام فرمایید.');
        return false;
    }
}
