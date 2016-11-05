/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
    var classify = '';
    var ztreeClick = function(event, treeId, treeNode, clickFlag) {
      //window.location.href = '/Group/index/classify/'+treeNode.id;
      classify = treeNode.id;
      //TableDatatablesAjax.init();
    }

	var setting = {
      async: {
        enable: true,
        url: '/ManageGroupClassify/getData'
      },
		data: {
			simpleData: {
				enable: true
			}
		},
        callback:{
          onClick:ztreeClick
        }
	};

    $.fn.zTree.init($("#treeDemo"), setting);
		
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
          // execute some code on ajax data load
          $('[data-toggle="confirmation"]').each(function () {
            $(this).confirmation();
            $(this).on('confirmed.bs.confirmation', function () {
              $.ajax({
                url: CONFIG['MODULE'] + '/Group/Remove',
                type: 'POST',
                data: {
                  ids: $(this).data('id'),
                  classify:classify,
                },
                beforeSend: function () {

                },
                success: function (res, response, status) {
                  if (res.error < 0) {
                    App.notification({
                      type: 'danger',
                      icon: 'warning',
                      message: '删除失败' + res.msg,
                      container: $(".page-content-col .portlet-title"),
                      place: 'prepend',
                      closeInSeconds: 1.5
                    });
                    return;
                  }

                  App.notification({
                    type: 'success',
                    icon: 'success',
                    message: '删除成功',
                    container: $(".page-content-col .portlet-title"),
                    place: 'prepend',
                    closeInSeconds: 1.5
                  });
                  $('.filter-submit').trigger('click');
                }
              });
            });
          });
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
            "url": CONFIG['MODULE'] + '/Group/getList', // ajax source
          },
          classify:104,
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
              data: 'ispublic'
            },

            {data: 'create_time'},
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
            [3, "desc"]
          ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return ' <span class="label label-sm label-' + (data == '公共' ? 'success' : 'info') + '">' + data + '</span>'
              },
              "targets": 2
            },
            {
              "render": function (data, type, row) {
                return data == 1 ? "执行中" : "空闲";
              },
              "targets": 4
            },
            {
              "render": function (data, type, row) {

                return '<a href="./edit/id/' + row.id + '" class="btn dark btn-sm btn-outline margin-bottom-5"> <i class="fa fa-edit"></i> 编辑 </a>'
                  + '<a data-toggle="confirmation" data-id="' + row.id + '" data-title="删除后不可恢复！！" data-btn-ok-label="Continue" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Stoooop!" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger" class="btn red btn-sm btn-outline margin-bottom-5"> <i class="fa fa-remove"></i> 删除 </a>'
                  + '<a  href="./single/tid/' + row.id + '" class="btn blue btn-sm btn-outline margin-bottom-5"> <i class="fa fa-object-ungroup"></i> 用例管理 </a>'
                  + '<a data-toggle="modal" data-title="' + row.name + '" data-id="' + row.id + '"  data-status="' + row.status + '"  ' + (row.status == 1 ? 'disabled' : '') + '   class="exec_btn btn yellow btn-sm btn-outline margin-bottom-5"> <i class="fa fa-rotate-left"></i> 执行 </a>'
                  + '<a href="./execute_history/tid/' + row.id + '" class="btn green-jungle btn-sm btn-outline margin-bottom-5"> <i class="fa fa-history"></i> 执行记录 </a>';

              },
              "targets": 5
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
          App.notification({
            type: 'danger',
            icon: 'warning',
            message: 'Please select an action',
            container: grid.getTableWrapper(),
            place: 'prepend',
            closeInSeconds: 1.5
          });
        } else if (grid.getSelectedRowsCount() === 0) {
          App.notification({
            type: 'danger',
            icon: 'warning',
            message: 'No record selected',
            container: grid.getTableWrapper(),
            place: 'prepend',
            closeInSeconds: 1.5
          });
        }
      });

      //grid.setAjaxParam("customActionType", "group_action");
      //grid.getDataTable().ajax.reload();
      //grid.clearAjaxParams();
    };

    var exec = function () {
      var $modal_exec = $('#exec');

      $('body').on('click', '.exec_btn', function () {
        var el = $(this);
        if (el.data('status') == 1)return;
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
            url: CONFIG['MODULE'] + '/Group/Execute',
            type: 'POST',
            data: $(form).serialize(),
            beforeSend: function () {},
            success: function (res, response, status) {
              $modal_exec.modal('hide');

              App.unblockUI($modal_exec);
              if (res.error < 0) {
                return App.warning('Execute fail, Error: ' + res.msg);
              }

              var r = res.data && JSON.parse(res.data);
              if (r && !r.isSucess) {
                return App.warning('Execute fail, Error: ' + r.msg);
              }
              App.warning('执行成功');
            },
            error: function () {
              App.unblockUI($modal_exec);
            }
          });
          return false;
        }
      });
    };
    return {
      init: function () {
        initPickers();
        handleRecords();
        exec();
      }
    };
  }();


  TableDatatablesAjax.init();
});
