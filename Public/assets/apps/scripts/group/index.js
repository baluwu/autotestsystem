/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var grid = new Datatable();

  function getCheckedGroupId() {
    var ck_t = $.fn.zTree.getZTreeObj("treeDemo").getCheckedNodes();
    var ck_t_id = [];
    $.each(ck_t, function(i, el){
      if (!el.isParent) {
        ck_t_id[ck_t_id.length] = el['group_id'];
      }
    });
    return ck_t_id.join(',');
  }

  function reloadGrid(group_id) {
    grid.setAjaxParam("group_id", group_id);
    grid.getDataTable().ajax.reload();
    grid.clearAjaxParams();
  }

  var setting = {
      check: { enable: true },
      async: {
        enable: true,
        url: '/ManageGroupClassify/getData/group/1'
      },
      data: {
        simpleData: { enable: true }
      },
      callback:{
        onCheck: function(treeId, treeNode) {
            var gids = getCheckedGroupId();
            reloadGrid(gids);
        },
        beforeClick: function(treeId, treeNode) {
          var zTree = $.fn.zTree.getZTreeObj("treeDemo");
          if (treeNode.isParent) {
            zTree.expandNode(treeNode);
          }
          var gids = getCheckedGroupId();
          //zTree.checkNode(treeNode, !treeNode.checked, true);
          //reloadGrid(gids);
          return false;
        }
      }
	};

  $.fn.zTree.init($("#treeDemo"), setting);
		
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
        dataTable: {
          "bStateSave": true, 
          "lengthMenu": [
            [10, 20, 50, 100, 150, -1],
            [10, 20, 50, 100, 150, "All"] 
          ],
          "pageLength": 20, 
          "ajax": {
            "url": CONFIG['MODULE'] + '/Group/getList', 
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
      var $modal_exec = $('#J_task_single');
      $('.J_add_task').click(function() {
        var group_ids = getCheckedGroupId(); 

        $.ajax({
          'url': '/Group/getSingleByGroupId',
          'method': 'POST',
          'data': { group_ids: group_ids },
          'type': 'JSON',
          'success': function(r) {
            var body = [];

            if (!r.data || !r.data.length) {
              return App.warning('无用例');
            }

            if (r.data.length > 100) {
              return App.warning('已超出任务用例数最大限制: 100');
            }

            $.each(r.data, function(i, el) {
              var tr = '<tr>';
              
              tr += '<td><input type="checkbox" checked class="single-ckbx" data-sid="' + el.id + '" /></td>' + '<td>' + el.id + '</td><td>' + el.name + '</td><td>' + el.nlp + el.arc + '</td>'
              tr += '</tr>';

              body.push(tr);
            });

            $('#J_task_single_bd').html(body.join(''));
            var el = $(this);
            $modal_exec.find('.currName').text(el.data('title'));
            $modal_exec.find('[name="id"]').val(el.data('id'));
            $modal_exec.find('.tips').html("");
            $modal_exec.modal({'width': 1024});
          }
        });
      });

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
  $('.group-ckbx').click(function() {
    var isCheck = $(this).is(':checked');
    console.log(isCheck);
    $('.single-ckbx').each(function(i, el){
       if (isCheck) {
         $(el).attr('checked', true);    
       }
       else 
         $(el).removeAttr('checked');    
    });    
  })
});
