/**
 * Created by andy on 16/8/24.
 */
$(function () {

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

});

