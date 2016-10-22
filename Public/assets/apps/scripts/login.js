var Login = function () {

  var handleLogin = function () {

    $('.login-form').validate({
      errorElement: 'span', //default input error message container
      errorClass: 'help-block', // default input error message class
      focusInvalid: false, // do not focus the last invalid input
      rules: {
        username: {
          required: true,
          minlength: 2,
          maxlength: 20
        },
        password: {
          required: true,
          minlength: 6,
          maxlength: 30
        },
        remember: {
          required: false
        }
      },

      messages: {
        username: {
          required: "用户名必填" || "Username is required."
        },
        password: {
          required: "密码必填" || "Password is required."
        }
      },

      invalidHandler: function (event, validator) { //display error alert on form submit
        $('.alert-danger').show();
      },

      highlight: function (element) { // hightlight error inputs
        $(element)
          .closest('.form-group').addClass('has-error'); // set error class to the control group
      },

      success: function (label) {
        label.closest('.form-group').removeClass('has-error');
        label.remove();
      },

      errorPlacement: function (error, element) {
        error.insertAfter(element.closest('.input-icon'));
      },

      submitHandler: function (form) {
        loginSubmit();
        return false;
      }
    });
    var loginSubmit = function () {
      if ($('.login-form').validate().form()) {
        //$('.login-form').submit(); //form validation success, call ajax form submit
        $.ajax({
          url: CONFIG['MODULE'] + '/Login/checkManager',
          type: 'POST',
          data: $('.login-form').serialize(),
          beforeSend: function () {
            $.blockUI({
              message: "正在尝试登录",
              css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
              }
            });
          },
          success: function (data, response, status) {

            if (data > 0) {
              location.href = CONFIG['redirect_url']?CONFIG['redirect_url']:CONFIG['INDEX'];
              return;
            }
            $.unblockUI();
            $.blockUI({
              message: "管理员帐号或密码不正确",
              css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .9,
                color: '#fff'
              }
            });
            setTimeout($.unblockUI, 1000);

          },
        })
      }
    };
      $('.login-form input').keypress(function (e) {
        if (e.which == 13) {
          loginSubmit();
          return false;
        }
      });


      $('#forget-password').click(function () {
        //alert('请联系管理员');

        $.blockUI({
          message: "请联系管理员",
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
          }
        });

        setTimeout($.unblockUI, 1000);

      });


    };


    return {
      //main function to initiate the module
      init: function () {

        handleLogin();

        // init background slide images
        $('.login-bg').backstretch([
            "/Public/assets/pages/img/login/bg0.jpg",
            "/Public/assets/pages/img/login/bg1.jpg",
            "/Public/assets/pages/img/login/bg2.jpg",
            "/Public/assets/pages/img/login/bg3.jpg"
          ], {
            fade: 1000,
            duration: 8000
          }
        );


      }

    };

  }();

  jQuery(document).ready(function () {
    Login.init();
  });
