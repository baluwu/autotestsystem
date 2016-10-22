/**
 * Created by andy on 16/7/8.
 */



jQuery(document).ready(function () {

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
      name: {
        minlength: 2,
        maxlength: 20,
        required: true
      },
      property: {
        required: true,
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
      var form_temp=$(form).serializeArray();
      console.log(form_temp["submit"]);
      $.ajax({
        url: CONFIG['MODULE'] + '/Group/addGroup',
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {

        },
        success: function (res, response, status) {


          if (res.error >= 0 ) {
            location.href='/Group/index';
            return;
          }

          App.notification({
            type: 'danger',
            icon: 'warning',
            message:res.msg?res.msg:'未知错误！请检查内容后重新提交！',
            container:$(".page-content-col .portlet-title"),
            place: 'prepend',
            closeInSeconds:1500
          });

        }
      });
     return false;
    }
  });

});
