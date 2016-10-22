/**
 * Created by andy on 16/7/8.
 */



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
        required: true,
      },
      nlp: {
        required:"#type_switch:not(:checked)",
      },
      arc: {
        required:"#type_switch:checked",
      },
      'v1[]': {
        required: true,
        maxlength: 100,
      },
      'v2[]': {
        required: true,
        maxlength: 20,
      },
      'dept[]': {
        required: true,
      },
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
        url: CONFIG['MODULE'] + '/Group/single_save',
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {

        },
        success: function (res, response, status) {
          if (res.error < 0 ) {
            App.notification({
              type: 'danger',
              icon: 'warning',
              message: '添加失败'+res.msg,
              container:$(".page-content-col .portlet-title"),
              place: 'prepend',
              closeInSeconds: 1.5
            });
            return;
          }
            location.href='/Group/single/tid/'+CONFIG['tid'];
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



  validates.init();
  $('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
  });
});
