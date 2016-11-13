/**
 * Created by andy on 16/7/8.
 * W.G modified at 16/10/11
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
          $('.J_remove_single').confirmation();
          $('.J_remove_single').on('confirmed.bs.confirmation', function () {
            var id = $(this).attr('data-id');
            $.ajax({
                url: '/Single/Remove',
                type: 'POST',
                data: { id: id },
                success: function (res, response, status) {
                  if (res.error < 0) {
                    return App.warning( '删除失败' + res.msg, $(".page-content-col .portlet-title"));
                  }
                  App.ok( '删除成功', $(".page-content-col .portlet-title"));
                  $('.filter-submit').trigger('click');
                }
              });
          });
        },
        loadingMessage: 'Loading...',
        dataTable: {
          "bStateSave": true, 
          "lengthMenu": [
            [10, 20, 50],
            [10, 20, 50]
          ],
          "pageLength": 20, 
          "ajax": { "url": '/Single/getList' },
          keys: true,
          columns: [
            { data: 'id', orderable: false },
            { data: 'name', orderable: false },
            { data: 'nlp', orderable: false },
            { data: 'create_time'},
            { data: 'nickname', orderable: false },
            { class: "action-control", orderable: false, data: null, defaultContent: "" }
          ],
          "order": [ [3, "desc"] ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return "<span title='"+row.name+"'>"+row.short_name+"</span>";
              }, "targets": 1
            },
            {
              "render": function (data, type, row) {
                return '<span class="label label-' + (row.nlp ? 'success' : 'info') + '">' + (row.nlp ? 'NLP' : 'ASR') + '</span>';
              }, "targets": 2
            },
            {
              "render": function (data, type, row) {
                return row.create_time;
              }, "targets": 3
            },
            {
              "render": function (data, type, row) {
                return "<span title='"+row.nickname+"'>"+row.nickname+"</span>";
              }, "targets": 4
            },
            {
              "render": function (data, type, row) {
                return '<a href="javascript:;" data-id="' + row.id + '" data-name="' + row.short_name + '" class="J_play_single"><i class="glyphicon glyphicon-play"></i></a>' + 
                '<a data-toggle="confirmation" data-id="' + row.id + '" data-title="删除后不可恢复, 要继续么?" data-btn-ok-label="OK" data-btn-cancel-label="NO" class="J_remove_single"><i class="glyphicon glyphicon-remove"></i></a>' +
                '<a href="/Group/edit/id/' + row.id + '" target="_blank"><i class="glyphicon glyphicon-pencil"></i></a>';
              },
              "targets": 5
            }
          ]
        }
      });
    };

    var exec = function () {
      var $modal_exec = $('#exec');

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
          App.blockUI({ message: '执行中....', target: $modal_exec, overlayColor: 'none', cenrerY: true, boxed: true });

          var exec_type = $('#exec').find('[name="type"]').val();

          $.ajax({
            url: exec_type == 'group' ? '/Group/Execute' : '/Single/Execute',
            type: 'POST',
            data: $(form).serialize(),
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

              App.ok('执行成功');
            },
            error: function () {
              App.unblockUI($modal_exec);
            }
          });

          Cookies.set('IP', form.ip.value);
          Cookies.set('port', form.port.value);
          Cookies.set('interval', form.interval.value);

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

          $modal_exec = $('#J_task_single');

          $('.single-ckbx:checked').each(function(i, el) {
            single_ids.push($(el).attr('data-sid')); 
          });

          if (single_ids.length == 0) {
            return App.warning('请选择用例');
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
            url: '/Task/add',
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
  });

  function setCaseType() {
    var case_type = 'all';
    var is_nlp_checked = $('.type-nlp').hasClass('btn-success');
    var is_asr_checked = $('.type-asr').hasClass('btn-info');
    if (is_nlp_checked && !is_asr_checked) case_type = 'nlp';
    if (!is_nlp_checked && is_asr_checked) case_type = 'asr';
    $('#J_case_type').val(case_type);
  }

  $('.type-nlp').click(function() {
    var self = $(this),
      subling = $('.type-asr'),
      is_checked = !self.hasClass('btn-default');
      is_subling_checked = !subling.hasClass('btn-default');

    var addCls = is_checked ? 'btn-default' : 'btn-success';
    var rmCls = is_checked ? 'btn-success' : 'btn-default';
    self.removeClass(rmCls).addClass(addCls);
    if (is_subling_checked) {
      subling.removeClass('btn-info btn-success').addClass('btn-default');
    }
    setCaseType();
  });

  $('.type-asr').click(function() {
    var self = $(this),
      subling = $('.type-nlp'),
      is_checked = !self.hasClass('btn-default');
      is_subling_checked = !subling.hasClass('btn-default');

    var addCls = is_checked ? 'btn-default' : 'btn-info';
    var rmCls = is_checked ? 'btn-info' : 'btn-default';
    self.removeClass(rmCls).addClass(addCls);
    if (is_subling_checked) {
      subling.removeClass('btn-info btn-success').addClass('btn-default');
    }
    setCaseType();
  });

  $('.type-nlp, .type-asr').click(function() {
    $('.filter-submit').trigger('click');
  });

  $('body').on('click', '.J_play_single', function() {
    var that = $(this);
    $('#exec').modal();
    $('#exec').on('shown.bs.modal', function () {
      var self = $(this);
      self.find('.modal-title').text('执行用例 [' + that.attr('data-name') + ']');
      self.find('.form-interval').hide();
      self.find('[name="id"]').val(that.attr('data-id'));
      self.find('[name="type"]').val('single');
      self.find('.tips').html("");

      self.find('#interval').val(Cookies.get('interval') || '1')
      self.find('.form-interval').hide();
      self.find('#ip').val(Cookies.get('IP') || '');
      self.find('#port').val(Cookies.get('port') || '8080');
    })
  }); 

  $('.J_view_cases').click(function() {
    $('.case-list').toggle();
  });
});
