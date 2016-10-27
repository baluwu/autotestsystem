/**
 * Created by andy on 16/7/8.
 */



jQuery(document).ready(function () {

  var validates = function () {

    var tpl = $("#validates_tpl").html();
    var count = $("#validates_table tbody tr").size();
    var add = function (tpl) {
      var currtr = $("#validates_table tbody").append(tpl).children('tr').last();
      currtr.find('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
      });
      currtr.find("input").val('');
      currtr.children('td').eq(0).text(++count);
    };
    var addEvet = function () {
      $("#validates_btn_add").on('click', function () {
        add(tpl);
      });
      $("#validates_table").on('click', "a.remove", function () {
        if ($("#validates_table tbody tr").size() > 1) {
          $(this).parents('tr').remove();
        }

      });
    };

    return {
      //main function to initiate the module
      init: function () {
        addEvet();
      }
    };
  }();


  $("input[name='type_switch']").on('switchChange.bootstrapSwitch', function () {
    $("#arc_warp,#nlp_warp").toggle();
  });


  $('#atsform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",  // validate all fields including form hidden input
    messages: {
      select_multi: {
        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
        minlength: jQuery.validator.format("At least {0} items must be selected")
      }
    },
    rules: {
      mc: {
        minlength: 2,
        maxlength: 100,
        required: true
      },
      property: {
        required: true
      },
      nlp: {
        required:"#type_switch:not(:checked)",
      },
      arc: {
        required:"#type_switch:checked",
      },
      'v1[]': {
        required: true,
        maxlength: 100
      },
      'v2[]': {
        required: true,
        maxlength: 20
      },
      'dept[]': {
        required: true
      }
    },

    invalidHandler: function (event, validator) { //display error alert on form submit

      App.scrollTo($('#atsform'), -200);
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

    submitHandler: function (form) {

      $.ajax({
        url: CONFIG['MODULE'] + '/Single/updateSingle/id/'+CONFIG['ID'],
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {

        },
        success: function (res, response, status) {

          if (res.error >= 0) {
            location.href = '/Single/index';
            return;
          }

          App.notification({
            type: 'danger',
            icon: 'warning',
            message: res.msg?res.msg:'未知错误！请检查内容后重新提交！',
            container: $(".page-content-col .portlet-title"),
            place: 'prepend',
            closeInSeconds: 1.5
          });

        }
      });
      return false;
    }
  });

  $("#arc_upload").dropzone({
    url: "/Single/uploadFile",
    maxFilesize: 1,//单位MB
    uploadMultiple:false,
    dictInvalidFileType:'非法文件',
    dictDefaultMessage:'拖拽文件到此处',
    acceptedFiles:'audio/*',
   init: function () {

   },
    complete: function (file) {
      //console.log(file);
    },

    error: function (file, message) {
      alert(message);
      return false;
    },
    success: function (file) {
      if(file.status!="success"){

        App.notification({
          type: 'danger',
          icon: 'warning',
          message: '上传失败',
          container: $(".page-content-col .portlet-title"),
          place: 'prepend',
          closeInSeconds: 1.5
        });
        return;
      }
      var res=JSON.parse(file.xhr.responseText);
      if (res.status==0){
        App.notification({
          type: 'danger',
          icon: 'warning',
          message: res.msg,
          container: $(".page-content-col .portlet-title"),
          place: 'prepend',
          closeInSeconds: 1.5
        });
        return;
      }
      $('input[name="arc"]').val(res.path);
      $('#arc_upload').html(res.path);




      console.log(file.xhr.responseText);
      console.log(file);
    },
    addedfile: function (file) {
      console.log(file);
    }
  });
  
  //执行，用户可以为此次执行添加注释，此时的执行，不记录结果到数据库，而是将结果返回到页面，这是一个阻塞的执行过程
  var $modal_exec = $('#exec');
  $('body').on('click', '#exec_btn', function () {
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
            url: CONFIG['MODULE'] + '/Single/ExecuteSingle',
            type: 'POST',
            data: $(form).serialize(),
            beforeSend: function () {

            },
            success: function (res, response, status) {
              $modal_exec.modal('hide');
              App.unblockUI($modal_exec);
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



  validates.init();
  $('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
  });
});
