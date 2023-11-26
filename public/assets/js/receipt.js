$(document).ready(function(){
    // First we convert html to image to keep all styles
    var node = document.getElementById('receipt');
    domtoimage.toJpeg(node, {quality: 1})
        .then(function (dataUrl) {
            var img = new Image();
            img.src = dataUrl;
            // put image to an element
            jQuery('#receipt-image').html(img);
            // Hide receipt
            jQuery('#receipt').hide();
            hideLoading();
        })
        .catch(function (error) {
            hideLoading();
            console.error('خطایی رخ داد. صفحه را دوبارهخ بارگذاری نمایید', error);
        });


    /**
     * Print Recipt
     * Creates an image from html then prints image.
     */
    jQuery(document).on('click', '#print-receipt', function(){
        // print image
        jQuery('#receipt-image').printThis({importCSS: false});

        // This check is needed determine if receipt is shown during submit process
        // or is in show page
        if(jQuery('#receipt-image').length){
            jQuery('#finish-link').show();
        }
    });


    /**
     * Listener on finish link
     */
    jQuery(document).on('click', '#finish-link-2', function(e){
        let link = jQuery(this).attr('href');
        e.preventDefault();
        Swal.fire({text: 'درخواست شما برای تعاونی جهت بررسی و ارسال بسته ارسال شده است', confirmButtonText: 'باشه'})
            .then(function () {
                window.location.href = link;
            });
    });



});