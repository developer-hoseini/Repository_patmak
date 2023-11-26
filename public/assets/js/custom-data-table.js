/* 
 * @author Mahdi Poustini
 * This is custom datatable runner with prefered options base on Data table.
 * 
 */

var DT = function () {
    
    var ajaxParams = {};
    var resetUrl = null;
    var table;
    
    return {
        table,
        /**
         * This function loads data table with defined options
         * @param {String} base_url 
         * @param {String} path Path to retiruve data
         * @param {Stirng} tableSelector Table Selector (id or class)
         * @param {Array} columns Columns to show by order
         * @param {Array} columnDefs Defenitions of columns - optional
         */
        init: function (base_url, path, tableSelector, columns, columnDefs) {
            
            var _columnDefs = (columnDefs) ? columnDefs : [];

            var _columns = [];

            // prepare columns to show
            columns.forEach(function (item, index) {
                _columns[index] = {"data": item};
            });

            this.table = $(tableSelector).DataTable({
                "searching": false,

                "ajax": {
                    "url": base_url + path,
                    "type": "POST",
                    "data": function (data) { // add request parameters before submit
                        $.each(ajaxParams, function (key, value) {
                            data[key] = value;
                        });
                    }
                },
                "processing": true,
                "serverSide": true,
                "columns": _columns,
                "autoWidth": false,
                "columnDefs": _columnDefs,
                "lengthChange": true, // number of row select box
                "pageLength": 5,
                "dom": "Bfrtip", // [lBfrtip , Bfrtip]
                lengthMenu: [
                    [ 5, 10, 25 ],
                    [ '5 ردیف' ,'10 ردیف', '25 ردیف' ]
                ],
                select: {
                    style: 'multi'
                },
                "buttons": [
                    'pageLength',
                ],
                "language": {
                    // "url": base_url + "/assets/plugins/DataTables/DataTables-1.10.20/langs/persian.json",
                    "buttons": {
                        "pageLength": "نمایش %d ردیف"
                    },
                    "sEmptyTable":     "هیچ داده‌ای در جدول وجود ندارد",
                    "sInfo":           "نمایش _START_ تا _END_ از _TOTAL_ ردیف",
                    "sInfoEmpty":      "نمایش 0 تا 0 از 0 ردیف",
                    "sInfoFiltered":   "(فیلتر شده از _MAX_ ردیف)",
                    "sInfoPostFix":    "",
                    "sInfoThousands":  ",",
                    "sLengthMenu":     "نمایش _MENU_ ردیف",
                    "sLoadingRecords": "در حال بارگزاری...",
                    "sProcessing":     "در حال پردازش...",
                    "sSearch":         "جستجو:",
                    "sZeroRecords":    "رکوردی با این مشخصات پیدا نشد",
                    "oPaginate": {
                        "sFirst":    "برگه‌ی نخست",
                        "sLast":     "برگه‌ی آخر",
                        "sNext":     "بعدی",
                        "sPrevious": "قبلی"
                    },
                    "oAria": {
                        "sSortAscending":  ": فعال سازی نمایش به صورت صعودی",
                        "sSortDescending": ": فعال سازی نمایش به صورت نزولی"
                    }
                }

            });

            $(document).on('click', '.filter-submit', function () {

                var url = new URL(window.location.href);
                var search_params = url.searchParams;
                const filterInput = document.getElementsByClassName('filter-input');
                for (var i = 0; i < filterInput.length; i++) {
                    
                    if (filterInput[i].value.length){     
                        // We must set or append query param
                        if (window.location.href.indexOf(filterInput[i].name + '=') === -1){
                            // Append if query param not exists
                            search_params.append(filterInput[i].name, filterInput[i].value);

                        } else {
                            // Set new value if exists
                            search_params.set(filterInput[i].name, filterInput[i].value); // set new value                                                                         
                        }

                    } else {
                        // We must delete a query param
                        search_params.delete(filterInput[i].name);
                    }
                      
                }
                
                url.search = search_params.toString(); // change the search property of the main url                                
                url = url.toString(); // set new url

                window.location.replace(url);
                //table.ajax.reload();
            });

            $(document).on('click', '.filter-reset', function () {
                
                if(resetUrl){
                    window.location.replace(resetUrl);
                } else {
                    alert("Use function setRestUrl and set urlReset before initilize data table.");
                }
                
//                ajaxParams = {};
//                const filterInput = document.getElementsByClassName('filter-input');
//                document.getElementsByClassName('filter-input');
//                for (var i = 0; i < filterInput.length; i++) {
//                        filterInput[i].value = '';
//                }
//                table.ajax.reload();
            });

            return table;
        },
        setResetUrl: function(ResetUrl){
            resetUrl = ResetUrl;
        },
        applyUrlParamsToFilters: function(){
            var urlParams = new URLSearchParams(window.location.search); // Gets all query params
            
            urlParams.forEach(function (value, index) {
                ajaxParams[index] = value; // Add each query params to Ajax params
                $('input[name=' + index + ']').val(value); // Copy value of each query param to concerned input filter
            });
        },
        addButton: function(buttonText, buttonAction){
            this.table.button().add( null, {
                text: buttonText,
                action: buttonAction
            });
        }

    };

};
