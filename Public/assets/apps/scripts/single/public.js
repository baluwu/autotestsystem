/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
    var TableDatatablesAjax = function () {

      var initPickers = function () {
        //init date pickers
        $('#datepicker').datepicker({
          rtl: App.isRTL(),
          orientation: "bottom auto",
          format: "yyyy-mm-dd",
          todayHighlight: true,
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
                        data: 'status',
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
                                return "<span title='"+row.name+"'>"+row.short_name+"</span>";
                            },
                            "targets": 1
                        },
                        {
                            "render": function (data, type, row) {
                                if(data){
                                    return "<span title='"+row.nlp+"'>"+row.short_nlp+"</span>";
                                }else{
                                    return "<audio src='"+row.arc+"' controls>"  
                                }
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
                                  var _str = '<div class="btn-group-red btn-group">\
                                        <button data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="btn btn-sm md-skip dropdown-toggle" type="button">\
                                            『' +data[0].v1+'』' + data[0].dept + '『'+ data[0].v2 + '』\
                                        </button>\
                                        <ul role="menu" class="dropdown-menu-v2">\
                                        ';
                                    for(var i in data){
                                    _str += '<li><a href="javascript:;">『' +data[i].v1+'』' + data[i].dept + '『'+ data[i].v2 + '』</a></li>';  
                                    }
                                    _str+='</ul>\
                                        </div>';
                                    return _str;
                            },
                            "targets": 3
                        },
                      {
                        "render": function (data, type, row) {
                          return data==1?"执行中":"空闲";
                        },
                        "targets": 6
                      },
                        {
                            "render": function (data, type, row) {
                                  return '<div class="btn-group">\
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">\
                                        操作 <span class="caret"></span>\
                                    </button>\
                                    <ul class="dropdown-menu" role="menu">\
                                        <li><a  data-toggle="modal" data-title="' + row.name + '" data-id="' + row.id + '" data-status="' + row.status + '"  '+(row.status==1?'disabled':'')+'   class="exec_btn btn yellow btn-sm btn-outline margin-bottom-5"> <i class="fa fa-rotate-left"></i> 执行 </a></li>\
                                        <li><a href="./execute_history_pub/id/' + row.id + '"  class="btn green-jungle btn-sm btn-outline margin-bottom-5"> <i class="fa fa-history"></i> 执行记录 </a></li>\
                                    </ul>\
                                    </div>';
                            },
                            "targets": 7
                        }
                    ]
                }
            });


            //执行
            var $modal_exec = $('#exec');

            $('body').on('click', '.exec_btn', function () {
                var el = $(this);
              if(el.data('status')==1)return;
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
                  App.blockUI({
                    message: '执行中....',
                    target: $modal_exec,
                    overlayColor: 'none',
                    cenrerY: true,
                    boxed: true
                  });
                    $.ajax({
                        url: CONFIG['MODULE'] + '/Single/ExecuteSingle',
                        type: 'POST',
                        data: $(form).serialize(),
                        beforeSend: function () {

                        },
                        success: function (res, response, status) {
                          App.unblockUI($modal_exec);
                            $modal_exec.modal('hide');
                            if (res.error < 0) {
                                App.notification({
                                    type: 'danger',
                                    icon: 'warning',
                                    message: '执行失败' + res.msg,
                                    container: $(".page-content-col .portlet-title"),
                                    place: 'prepend',
                                    closeInSeconds: 1.5
                                });
                                return;
                            }

                            App.notification({
                                type: 'success',
                                icon: 'success',
                                message: '执行成功',
                                container: $(".page-content-col .portlet-title"),
                                place: 'prepend',
                                closeInSeconds: 1.5
                            });

                        },
                      error: function () {
                        App.unblockUI($modal_exec);
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
                    App.notification({
                        type: 'danger',
                        icon: 'warning',
                        message: 'Please select an action',
                        container: grid.getTableWrapper(),
                        place: 'prepend'
                    });
                } else if (grid.getSelectedRowsCount() === 0) {
                    App.notification({
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
