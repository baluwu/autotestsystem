/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
    var TableDatatablesAjax = function () {

        var initPickers = function () {
            //init date pickers
            $('.date-picker').datepicker({
                rtl: App.isRTL(),
                autoclose: true
            });
        };

        var handleRecords = function () {

            var grid = new Datatable();

            grid.init({
                src: $("#datatable_ajax"),
                onSuccess: function (grid, response) {
                    // grid:        grid object
                    // response:    json object of server side ajax response
                    // execute some code after table records loaded
                },
                onError: function (grid) {
                    // execute some code on network or other general error
                },
                onDataLoad: function (grid) {
                    //data-toggle="confirmation"

                },
                loadingMessage: 'Loading...',
                dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

                    // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                    // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
                    // So when dropdowns used the scrollable div should be removed.
                    //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                    "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

                    "lengthMenu": [
                        [10, 20, 50, 100, 150, -1],
                        [10, 20, 50, 100, 150, "All"] // change per page values here
                    ],
                    "pageLength": 20, // default record count per page
                    "ajax": {
                        "url": CONFIG['MODULE'] + '/Single/getHistory?id=' + CONFIG['ID'] // ajax source
                    },
                    keys: true,
                    columns: [
                        {
                            data: 'id',
                            orderable: false
                        },
                        {
                            data: 'ip',
                            orderable: false
                        },
                        {
                            data: 'port',
                            orderable: false
                        },

                        {
                            data: 'status'
                        },
                        {data: 'exec_start_time'},
                        {
                            data: 'uid',
                            class: "text-center",
                            orderable: false
                        },
                        {
                            class: "action-control",
                            orderable: false,
                            data: null,
                            defaultContent: ""
                        },
                    ],
                    "order": [
                        [4, "desc"]
                    ],
                    "columnDefs": [
                        {
                            "render": function (data, type, row) {
                                return '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                                    ' <input type="checkbox" class="checkboxes" value="' + data + '" />' +
                                    '<span></span>' +
                                    ' </label>';
                            },
                            "targets": 0
                        }, {
                            "render": function (data, type, row) {
                                return ' <span class="label label-sm label-' + (data == 2 ? 'success' : 'danger') + '">' + (data == 2 ? '成功' : '失败') + '</span>'
                            },
                            "targets": 3
                        },
                        {
                            "render": function (data, type, row) {
                                return row.nickname ? row.nickname : row.manager ? row.manager : data
                            },
                            "targets": 5
                        },
                        {
                            "render": function (data, type, row) {

                                return '<a target="_blank" href="/Single/execute_history_show/id/' + data.id + '" class="btn green-jungle btn-sm btn-outline margin-bottom-5"> <i class="fa fa-info-circle"></i> 查看 </a>';

                            },
                            "targets": 6
                        }
                    ]
                }
            });


            // handle group actionsubmit button click
            grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {

                e.preventDefault();
                var action = $(".table-group-action-input", grid.getTableWrapper());
                if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                    grid.setAjaxParam("customActionType", "group_action");
                    grid.setAjaxParam("customActionName", action.val());
                    grid.setAjaxParam("id", grid.getSelectedRows());
                    grid.getDataTable().ajax.reload();
                    grid.clearAjaxParams();
                } else if (action.val() == "") {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend',
                        closeInSeconds: 1.5
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend',
                        closeInSeconds: 1.5
                    });
                }
            });


            $("#execute_diff").on('click', function () {
                if ($('.checkboxes:checked').size() < 2) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'less then 2 records selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend',
                        closeInSeconds: 1.5
                    });
                    return false;
                }
                var _ids = [];
                $('.checkboxes:checked').each(function () {
                    _ids.push($(this).val());
                });
                $(this).attr('href', '/Single/execute_history_diff/id/' + CONFIG['ID'] + '?history_ids=' + _ids.join(','));
            });

        };

        return {

            //main function to initiate the module
            init: function () {

                initPickers();
                handleRecords();
            }

        };

    }();


    TableDatatablesAjax.init();
});
