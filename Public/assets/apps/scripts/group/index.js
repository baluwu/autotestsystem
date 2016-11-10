/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var grid = new Datatable();
  window.grid = grid;

  function getTree() { return $.fn.zTree.getZTreeObj("J_ztree"); }
  function getCheckedGroupId() {
    var ck_t = getTree().getCheckedNodes();
    var ck_t_id = [];
    $.each(ck_t, function(i, el){
      if (el.level==2) {
        ck_t_id[ck_t_id.length] = el['group_id'];
      }
    });

    return ck_t_id.join(',');
  }

  var TableDatatablesAjax = function () {

    var initPickers = function () {
      $('#datepicker').datepicker({
        rtl: App.isRTL(),
        orientation: "bottom auto",
        format: "yyyy-mm-dd",
        todayHighlight: true,
        autoclose: true
      });
    };

    var handleRecords = function () {

      grid.init({
        src: $("#datatable_ajax"),
        onSuccess: function (grid, response) {},
        onError: function (grid) { },
        onDataLoad: function (grid) {
          $('[data-toggle="confirmation"]').each(function () {
            $(this).confirmation();
            $(this).on('confirmed.bs.confirmation', function () {
              $.ajax({
                url: '/Single/Remove',
                type: 'POST',
                data: {
                  ids: $(this).data('id')
                },
                success: function (res, response, status) {
                  if (res.error < 0) {
                    return App.warning( '删除失败' + res.msg, $(".page-content-col .portlet-title"));
                  }
                  App.warning('删除成功', $(".page-content-col .portlet-title"));
                  $('.filter-submit').trigger('click');
                }
              });
            });
          });
        },
        loadingMessage: 'Loading...',
        dataTable: {
          "bStateSave": true, 
          "lengthMenu": [
            [10, 20, 50, -1],
            [10, 20, 50, "All"] 
          ],
          "pageLength": 20, 
          "ajax": { "url": '/Single/getList', "type": 'POST', data: { group_ids: 0 } },
          keys: true,
          columns: [
            { data: 'id', orderable: false },
            { data: 'name', orderable: false },
            { data: 'nlp', orderable: false },
            { data: 'nickname' },
            { data: 'create_time'},
            { class: "action-control", orderable: false, data: null, defaultContent: "" }
          ],
          "order": [ [4, "desc"] ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return "<span title='"+row.name+"'>"+row.short_name+"</span>";
              }, "targets": 1
            },
            {
              "render": function (data, type, row) {
                return '<span class="label label-' + (row.nlp ? 'default' : 'info') + '">' + (row.nlp ? 'NLP' : 'ASR') + '</span>';
              }, "targets": 2
            },
            {
              "render": function (data, type, row) {
                return "<span title='"+row.nickname+"'>"+row.nickname+"</span>";
              }, "targets": 3
            },
            {
              "render": function (data, type, row) {
                return "<span title='"+row.create_time+"'>"+row.create_time+"</span>";
              }, "targets": 4
            },
            {
              "render": function (data, type, row) {
                return '<a href="javascript:;"><i class="fa fa-repeat"></i></a>' + 
                  '<a href="javascript:;"><i class="fa fa-times"></i></a>' + 
                  '<a href="javascript:;"><i class="fa fa-edit"></i></a>' + 
                  '<a href="javascript:;"><i class="fa fa-search"></i></a>';
                /*
                  '<div class="btn-group">\
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">\
                        操作 <span class="caret"></span>\
                      </button>\
                      <ul class="dropdown-menu" role="menu">\
                        <li><a href="./edit/id/' + row.id + '" class="btn dark btn-sm btn-outline margin-bottom-5"> <i class="fa fa-edit"></i>编辑</a></li>\
                        <li><a   data-toggle="confirmation" data-id="' + row.id + '" data-title="删除后不可恢复！！" data-btn-ok-label="Continue" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Stoooop!" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger" class="btn red btn-sm btn-outline margin-bottom-5"> <i class="fa fa-remove"></i>删除</a></li>\
                        <li><a  data-toggle="modal" data-title="' + row.name + '" data-id="' + row.id + '"  data-status="' + row.status + '"  ' + (row.status == 1 ? 'disabled' : '') + '  class="exec_btn btn yellow btn-sm btn-outline margin-bottom-5"> <i class="fa fa-rotate-left"></i>执行</a></li>\
                        <li><a href="./execute_history/id/' + row.id + '" class="btn green-jungle btn-sm btn-outline margin-bottom-5"> <i class="fa fa-history"></i>执行记录</a></li>\
                      </ul>\
                    </div>';
                    */
              },
              "targets": 5
            }
          ]
        }
      });

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
          App.warning( 'Please select an action', grid.getTableWrapper());
        } else if (grid.getSelectedRowsCount() === 0) {
          App.warning( 'No record selected', grid.getTableWrapper());
        }
      });
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
        errorElement: 'span', 
        errorClass: 'help-block help-block-error', 
        focusInvalid: false, 
        ignore: "", 
        errorPlacement: function (error, element) {
          if (element.is(':checkbox')) {
            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
          } else if (element.is(':radio')) {
            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
          } else {
            error.insertAfter(element); 
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').addClass('has-error'); 
        },
        unhighlight: function (element) { 
          $(element).closest('.form-group').removeClass('has-error'); 
        },
        success: function (label) {
          label.closest('.form-group').removeClass('has-error'); 
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

    var createTask = function () {
        $("#J_task_single form").validate({
        errorElement: 'span', 
        errorClass: 'help-block help-block-error', 
        focusInvalid: false, 
        ignore: "", 
        errorPlacement: function (error, element) {
          if (element.is(':checkbox')) {
            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
          } else if (element.is(':radio')) {
            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
          } else {
            error.insertAfter(element); 
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').addClass('has-error'); 
        },
        unhighlight: function (element) { 
          $(element).closest('.form-group').removeClass('has-error'); 
        },
        success: function (label) {
          label.closest('.form-group').removeClass('has-error'); 
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
          var single_ids = [];

          $('.single-ckbx:checked').each(function(i, el) {
            single_ids.push($(el).attr('data-sid')); 
          });

          if (single_ids.length == 0) {
            return App.warning('未选择用例');
          }

          $('#J_single_ids').val(single_ids.join(','));

          App.blockUI({
            message: '执行中....',
            target: $modal_exec,
            overlayColor: 'none',
            cenrerY: true,
            boxed: true
          });

          $.ajax({
            url: CONFIG['MODULE'] + '/Task/add',
            type: 'POST',
            data: $(form).serialize(),
            beforeSend: function () {},
            success: function (res, response, status) {
              $modal_exec.modal('hide');

              App.unblockUI($modal_exec);
              if (res.error) {
                return App.warning('Add Task fail, Error: ' + res.msg);
              }

              App.ok('Add task success!');
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
        createTask();
      }
    };
  }();

  TableDatatablesAjax.init();

  $("#run_at").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
  $('body').on('click', '.group-ckbx', function() {
    var isCheck = $(this).is(':checked');
    $('.single-ckbx').each(function(i, el) {
      var iCk = $(el).is(':checked');
      if (isCheck != iCk) {
        $(el).trigger('click');
      }
    });
  })
});
