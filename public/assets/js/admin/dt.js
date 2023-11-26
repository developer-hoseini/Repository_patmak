$(document).ready(function () {

    var baseUrl = (jQuery('base')) ? jQuery('base').attr('href') : "" + '/';
    var urlParams = new URLSearchParams(window.location.search);

    var status_title = urlParams.get('status_title');
    var path = "/admin/application";
    path += '?status_title=' + status_title;

    var resetUrl = window.location.origin + window.location.pathname +'?status_title=all';
    
    if(status_title == null){
        window.location.replace(resetUrl);
    }

    var params;
    var labels;
    var fType; // Filter types
    var columnDefs;
    var exludeFromFilter = ['actions', 'selectBox', 'domicile_shahr'];


    let progressFilterOptions = 'select-{"all":"همه","payed":"پرداخت شده","notpayed":"پرداخت نشده"}-'+status_title;
    params = ['application_id', 'person_type_title', 'p_name', 'p_lname', 'p_ncode','user_mobile', 'province_title', 'status_title', 'created_at',  'tracking_code'];
    labels = ["شناسه", "نوع", "نام", "نام خانوادگی", "کد ملی","شماره موبایل", "استان", "وضعیت", "تاریخ",  "کد رهگیری"];
    fType = ["","","", "", 'text',"", "", "","",   ""];
    columnDefs = [{width: "200px", targets: 3},{width: "70px", targets: 4}];

    params.push('actions');
    labels.push("عملیات");
    fType.push(null);

    createFilter(params, labels, fType, exludeFromFilter); // Creates filter inputs
    createLabels(labels); // Create each column label

    var _DT = new DT(); // New instance of custom data table
    _DT.setResetUrl(resetUrl);
    _DT.applyUrlParamsToFilters(); // Copies url query parameters in filter inputs
    var _table = _DT.init(baseUrl, path, "#myTable", params, columnDefs); // Creates data table

    if(endpoint == 'office'){
        _DT.addButton('درج کد رهگیری', function ( e, dt, button, config ) {
            // dt.ajax.reload();

            var Ids =[];
            $('#myTable tbody tr.selected').each(function(){
                var pos = dt.row(this).index();
                var row = dt.row(pos).data();
                Ids.push(row.id);
            });
            console.log(Ids);

            if(Ids.length === 0){
                Swal.fire({
                    text: 'هیچ رکوردی انتخاب نشده است. برای انتخاب یک رکورد روی ردیف رکورد مورد نظر کلیک کنید.',
                    icon: 'error',
                    confirmButtonText: 'باشه'
                });
                return false;
            }

            Swal.fire({
                title: 'درج کد رهگیری پستی',
                html: '<p>لطفا کد رهگیری را برای رکوردهای انتخاب شده درج کنید</p>' +
                    '<input type="text" id="swal-ptc" class="swal2-input" maxlength="24" placeholder="کد رهگیری پستی">',
                showCancelButton: true,
                confirmButtonText: 'ثبت',
                cancelButtonText: 'لغو',
                showLoaderOnConfirm: true,
                onOpen: function () {
                    $('#swal-ptc').focus()
                },
                allowOutsideClick: () => !Swal.isLoading(),
                preConfirm: () => {
                    return new Promise(function (resolve) {

                        if($('#swal-ptc').val() == ''){
                            Swal.showValidationMessage('کد رهگیری نمیتواند خالی باشد');
                        }

                        resolve($('#swal-ptc').val())
                    })
                }
            }).then(function (tracking_code) {

                if(typeof tracking_code == "object" && tracking_code.value  ){
                    console.log('tracking_code is', tracking_code);
                    let url = BASE_URL + '/Office/Post/setTrackingCode';
                    let data = {tc: tracking_code.value , ids: Ids};
                    console.log(data);
                    $.post(url, data)
                        .done(function(result){
                            console.log('ajax success');
                            Swal.fire({
                                text: `تعداد ${result.updates} رکورد بروزرسانی شد`,
                                icon: 'success',
                                confirmButtonText: 'باشه'
                            });
                        })
                        .fail(function(){
                            Swal.fire({
                                text: 'بروز رسانی رکوردها با خطا مواجه شد',
                                icon: 'error',
                                confirmButtonText: 'باشه'
                            });
                        });

                }

                //  other wise return false to cancel
                // example: user may clicked the cancel button
                return false;

            }).catch(function(error){
                console.log('error', error);
            })

        });
    }

    $('#myTable tbody').on( 'click', '.select', function () {
        console.log('clicked');
        $(this).closest('tr').toggleClass('selected');
    } );

});
            
/**
 * Creates input box for each given parameter in data table for filtering.
 * You must create a <div id="#tableBtnHolder"> and one <tr id="#filters">
 * @param {Mixed} items
 * @param {Mixed} excludes
 * @returns {undefined}
 */            
function createFilter(items, lables, fType, excludes) {
    var filter = '';
    for(var i=0; i< items.length; i++){
        if(excludes.indexOf(items[i]) < 0){ // Checks if it should not have filter
            var ft = fType[i].split("-"); // we do this to recognize select types
            switch(ft[0]){
                case 'text':
                    filter += '<th><input class="filter-input form-control form-control-sm" type="text" name="' + items[i] + '" placeholder="' + lables[i] + '"></th>';
                    break;
                case 'date':
                    filter += '<th><div class="form-row">'
                    + '<input class="filter-input form-control form-control-sm persianDatePicker" type="text" name="' + items[i] + '_from" placeholder="از تاریخ">'
                    + '<input class="filter-input form-control form-control-sm persianDatePicker" type="text" name="' + items[i] + '_to"   placeholder="تا تاریخ">'
                    + '</div></th>';
                    break;    
                case 'select':
                    filter += '<th><select class="filter-input form-control form-control-sm" name="' + items[i] + '" placeholder="' + lables[i] + '">';
                    var options = JSON.parse(ft[1]);
                    Object.keys(options).forEach(function(key) {
                        
                        if(ft[2] && key == ft[2])
                            filter += '<option value="'+ key +'" selected="selected">'+ options[key] +'</options>';
                        else    
                            filter += '<option value="'+ key +'">'+ options[key] +'</options>';

                    });
                    filter += '</select></th>';
                    break;

                default:
                    filter += '<th></th>';
                    break;
            }
            
            
        } else {
            filter += '<th></th>';
        }
    }

    document.getElementById('filters').innerHTML = filter;
    document.getElementById('tableBtnHolder').innerHTML = '<button class="btn btn-success btn-sm filter-submit mr-1">جستجو</button><button class="btn btn-danger btn-sm filter-reset">حذف فیلتر</button>';
    
    // Run persian date picker for inputs having class persianDatePicker
    $('.persianDatePicker').persianDatepicker({
        formatDate: 'YYYY-MM-DD'
    });
}

/**
 * Creates lable for data table
 * You must create a <tr id="#labels">
 * @param {type} items
 * @returns {undefined}
 */
function createLabels(items){
    var labels = '';
    items.forEach(function (item) {
        labels += '<th scope="col">' + item + '</th>';
    });

    document.getElementById('labels').innerHTML = labels;
}


/*This event listens to combobox changes for input name = sservice. And on change updates url. */
jQuery(document).ready(function(){
    jQuery(document).on('change', 'select[name="service"]', function(){
        console.log(this);
        var selectedService = jQuery("select[name=service]").val();
        var allowedServices = ['activate', 'transfer', 'repair','stuck', 'all'];
        if(allowedServices.indexOf(selectedService) >= 0){
            var newUrl = window.location.href.replace(/(service=).*?(&)/,'$1' + selectedService + '$2');
            window.location.replace(newUrl);
        }
    });
});