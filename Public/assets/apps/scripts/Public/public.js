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
                        "url": CONFIG['MODULE'] + '/Single/getListPub', // ajax source
                    },
                    keys: true,
                    columns: [
                        {
                            data: 'id',
                            orderable: false
                        },
                        {
                            data: 'name',
                            orderable: false
                        },
                        {
                            data: 'nlp',
                            orderable: false
                        },

                        {
                            data: 'validates',
                            orderable: false
                        },
                        {data: 'create_time'},
                        {
                            data: 'uid',
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
                                return data ? data : row.arc
                            },
                            "targets": 2
                        },
                        {
                            "render": function (data, type, row) {
                                return row.nickname ? row.nickname : row.manager ? row.manager : data
                            },
                            "targets": 5
                        },
                        {
                            "render": function (data, type, row) {
                                var _str = [];
                                $.each(data, function (k, v) {
                                    _str.push('<li>' + v.v1 + v.dept + v.v2 + '</li>');
                                });
                                return _str.join('');
                            },
                            "targets": 3
                        },
                        {
                            "render": function (data, type, row) {

                                return '<a  data-toggle="modal" data-title="' + data.sname + '" data-id="' + data.id + '"  class="exec_btn btn yellow btn-sm btn-outline margin-bottom-5"> <i class="fa fa-rotate-left"></i> 执行 </a>'
                                    + '<a href="./execute_history/id/' + data.id + '" class="btn green-jungle btn-sm btn-outline margin-bottom-5"> <i class="fa fa-history"></i> 执行记录 </a>';

                            },
                            "targets": 6
                        }
                    ]
                }
            });


            //执行
            var $modal_exec = $('#exec');

            $('body').on('click', '.exec_btn', function () {
                var el = $(this);
                $modal_exec.find('.currName').text(el.data('title'));
                $modal_exec.find('[name="id"]').val(el.data('id'));
                $modal_exec.find('.tips').html("");
                $modal_exec.modal();
            });

            $("#exec form").validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                errorPlacement: function (error, element) {
                    if (element.is(':checkbox')) {
                        error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
                    } else if (element.is(':radio')) {
                        error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },
                rules: {
                    ip: {
                        minlength: 7,
                        maxlength: 20,
                        required: true
                    },
                    port: {
                        minlength: 1,
                        maxlength: 6,
                        number: true
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: CONFIG['MODULE'] + '/Single/ExecuteSingle',
                        type: 'POST',
                        data: $(form).serialize(),
                        beforeSend: function () {

                        },
                        success: function (res, response, status) {
                            $modal_exec.modal('hide');
                            if (res.error < 0) {
                                App.alert({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: '执行失败' + res.msg,
                                    container: $(".page-content-col .portlet-title"),
                                    place: 'prepend',
                                    closeInSeconds: 1.5
                                });
                                return;
                            }

                            App.alert({
                                type: 'success',
                                icon: 'success',
                                message: '执行成功',
                                container: $(".page-content-col .portlet-title"),
                                place: 'prepend',
                                closeInSeconds: 1.5
                            });

                        }
                    });
                    return false;
                }
            });
            $modal_exec.on('click', '.btn.exec_ok', function () {
                //$modal_exec.modal('loading');
                //setTimeout(function(){
                //  $modal_exec
                //    .modal('loading')
                //    .find('.modal-body .tips')
                //    .html('<div class="alert alert-info fade in">' +
                //    'success!<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                //    '</div>');
                //}, 1000);
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
                        place: 'prepend'
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    App.alert({
                        type: 'danger',
                        icon: 'warning',
                        message: 'No record selected',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                }
            });

            //grid.setAjaxParam("customActionType", "group_action");
            //grid.getDataTable().ajax.reload();
            //grid.clearAjaxParams();
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
