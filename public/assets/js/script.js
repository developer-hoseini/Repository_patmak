/**
 * This function is a custom helper to quickly call swalerror.
 * 
 * @author Poustini
 * 
 * @param {string} text 
 * @param {string} title 
 * @param {string} buttonText 
 */
function swalError(text, title, buttonText) {

    const options = new Object();
    options.icon = 'error';
    options.confirmButtonText = (buttonText === undefined) ? "باشه" : buttonText; // set default value for buttonText
    // Check title
    if(title !== undefined){ // Check if title is set
        options.title = title;
    }
    // Check text
    if (typeof text === 'string') {
        options.text = text;
    } else {
        options.html = array_to_html(text);
    }
    Swal.fire(options);
}

/**
 * This function is a custom helper to quickly call swalsuccess.
 * 
 * @author Poustini
 * 
 * @param {string|Array} text 
 * @param {string} title 
 * @param {string} buttonText 
 */
 function swalSuccess(text, title, buttonText) {

    const options = new Object();
    options.icon = 'success';
    options.confirmButtonText = (buttonText === undefined) ? "باشه" : buttonText; // set default value for buttonText
    // Check title
    if(title !== undefined){ // Check if title is set
        options.title = title;
    }
    // Check text
    if (typeof text === 'string') {
        options.text = text;
    } else {
        options.html = array_to_html(text);
    }
    Swal.fire(options);
}

/**
 * 
 * @param {*} text 
 * @param {*} type 
 */
function ShowToast(text, type){
    $.Toast("", text, type, {
        has_icon:false,
        has_close_btn:true,
        stack: true,
        fullscreen:false,
        timeout:4500,
        sticky:false,
        has_progress:true,
        rtl:true,
    });
}

String.prototype.toEnglishDigit = function() { 
    var fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹']; 
    var en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9']; 
    var replaceString = this;
    for (var i = 0; i < fa.length; i++) { 
        replaceString = replaceString.replace(new RegExp(fa[i], "g"), en[i]); 
    } 
    return replaceString; 
}


function array_to_html(errors_array)
{
    let errors_html = '<ul>';
    errors_array.forEach(function(error_str){
        errors_html += '<li>' + error_str + '</li>';
    });
    return errors_html + '</ul>';
}



function logout()
{
    Swal.fire({
        title: 'از خروج اطمینان دارید؟',
        showCancelButton: true,
        confirmButtonText: 'خروج',
        cancelButtonText: 'لغو',
    })
    .then((result) => {
        let isConfirmed = result.hasOwnProperty('value') && result.value;
        if (isConfirmed) {
            window.location.href = '/logout';
        } else {
            return;
        }
    });
}


jQuery(document).ready(function(){
    jQuery('#logout').click(function(e){
        e.preventDefault();
        logout();
    });
});

/**
 * Fix length to 10
 * 
 * @param {number} ncode 
 * @returns string
 */
function fixNationalCodeNumber(ncode){  console.log('cdcdcdcd', ncode);   
    var zeros = 10 - ncode.toString().length;
    for(var i = 0; i < zeros; i++){
        ncode = "0" + ncode;
    }
    return ncode;
}