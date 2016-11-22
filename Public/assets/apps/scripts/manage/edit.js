/**
 * Created by andy on 16/7/8.
 */


function suggestPassword(passwd_form)
{
  // restrict the password to just letters and numbers to avoid problems:
  // "editors and viewers regard the password as multiple words and
  // things like double click no longer work"
  var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ";
  var passwordlength = 16;    // do we want that to be dynamic?  no, keep it simple :)
  var passwd = passwd_form.generated_password;
  passwd.value = '';

  for (var i = 0; i < passwordlength; i++) {
    passwd.value += pwchars.charAt(Math.floor(Math.random() * pwchars.length));
  }
  passwd_form.password.value = passwd.value;
  passwd_form.repassword.value = passwd.value;
  return true;
}

jQuery(document).ready(function () {




  var validates= function () {

    var tpl=$("#validates_table tbody").html();
    var count=1;
    var add= function (tpl) {
      $("#validates_table tbody").append(tpl).children('tr').last().find('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
      });
      $("#validates_table tbody tr").last().children('td').eq(0).text(++count);
    };
    var addEvet= function () {
      $("#validates_btn_add").on('click', function () {
        add(tpl);
      });
      $("#validates_table").on('click',"a.remove", function () {
        if( $("#validates_table tbody tr").size()>1){
          $(this).parents('tr').remove();
        }

      });
    };

    return {
      //main function to initiate the module
      init: function () {
        addEvet();
       new EmailAutoComplete({
          targetCls:"#email",
         parentCls:"#emailwarp",
          searchForm:"#atsform",
          mailArr:["@rokid.com","@qq.com","@gmail.com","@126.com","@163.com","@hotmail.com","@yahoo.com","@yahoo.com.cn","@live.com","@sohu.com","@sina.com"]
        });
      }
    };
  }();





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
        maxlength: 20
      },
      email: {
        email: true
      },
      password: {
        minlength: 8,
        maxlength: 20
      },
      repassword: {
        equalTo: "#password",
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
        url: CONFIG['MODULE'] + '/Manage/update',
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {

        },
        success: function (data, response, status) {


          if (data) {
            location.href='/Manage/index';
            return;
          }

          App.notification({
            type: 'danger',
            icon: 'warning',
            message: (data == -5)?_et('Username already exists'):(data == 0)?_et('Fail'):_et('Unknown error!Please check the content after submit again!'),
            container:$(".page-content-col .portlet-title"),
            place: 'prepend',
            closeInSeconds:1500
          });

        }
      });
     return false;
    }
  });



  validates.init();
  $('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
  });
});
