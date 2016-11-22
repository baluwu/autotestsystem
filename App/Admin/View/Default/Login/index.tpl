<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
  <meta charset="utf-8" />
  <title><?php _e('Login');?> | <?php _e('Auto Test System'); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport" />
  <meta content="andy" name="author" />
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->

  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN THEME GLOBAL STYLES -->
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css" />
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css" />
  <!-- END THEME GLOBAL STYLES -->
  <!-- BEGIN PAGE LEVEL STYLES -->
  <link href="/Public/assets/apps/css/login.css" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL STYLES -->
  <!-- BEGIN THEME LAYOUT STYLES -->
  <!-- END THEME LAYOUT STYLES -->
  <link rel="shortcut icon" href="/favicon.ico" />
  <script>
    var CONFIG = {
      'ROOT': '__ROOT__',
      'MODULE': '__MODULE__',
      'INDEX': '/Index',
      'redirect_url':'{$redirect_url}'
    };
  </script>

</head>
<!-- END HEAD -->

<body class=" login">
<!-- BEGIN : LOGIN ATS-->
<div class="user-login-ats">
  <div class="row bs-reset">
    <div class="col-md-6 bs-reset">
      <div class="login-bg">
        <img class="login-logo" src="/Public/assets/pages/img/logo-big.png" /> </div>
    </div>
    <div class="col-md-6 login-container bs-reset">
      <div class="login-content">
        <h1 class="font-green"><?php _e('Auto Test System'); ?></h1>
        <form action="javascript:;" class="login-form" method="post">
          <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            <span><?php _e('Enter any username and password'); ?>. </span>
          </div>
          <div class="row">
            <div class="col-xs-12 col-md-6">
              <input class="form-control form-control-solid placeholder-no-fix form-group" type="text" autocomplete="off" placeholder="Username" name="manager" required value="{$manager}"/> </div>
            <div class="col-xs-12 col-md-6">
              <input class="form-control form-control-solid placeholder-no-fix form-group" type="password" autocomplete="off" placeholder="Password" name="password" required/>

            </div>

          </div>
          <div class="row">
            <div class="col-sm-4">
              <div class="rem-password">
                <label class="rememberme mt-checkbox mt-checkbox-outline">
                  <input type="checkbox" name="remember" value="1" /> Remember me
                  <span></span>
                </label>
              </div>
            </div>
            <div class="col-sm-8 text-right">
              <div class="forgot-password">
                <a href="javascript:;" id="forget-password" class="forget-password">Forgot Password?</a>
              </div>
              <button class="btn green" type="submit">Sign In</button>
            </div>
          </div>
        </form>

      </div>
      <div class="login-footer">
        <div class="row bs-reset">
          <div class="col-xs-5 bs-reset">
            <ul class="login-social">
              <li>
                <a href="javascript:;">
                  <i class="fa fa-social-facebook"></i>
                </a>
              </li>
              <li>
                <a href="javascript:;">
                  <i class="fa fa-social-twitter"></i>
                </a>
              </li>
              <li>
                <a href="javascript:;">
                  <i class="fa fa-social-dribbble"></i>
                </a>
              </li>
            </ul>
          </div>
          <div class="col-xs-7 bs-reset">
            <div class="login-copyright text-right">
              <p>Copyright &copy; rokid.com 2016</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END : LOGIN PAGE 5-1 -->
<!--[if lt IE 9]>
<script src="/Public/assets/global/plugins/respond.min.js"></script>
<script src="/Public/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<!-- BEGIN CORE PLUGINS -->
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/Public/assets/global/plugins/js.cookie.min.js"></script>

<script src="/Public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/assets/global/plugins/jquery.blockui.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script src="/Public/assets/global/plugins/backstretch/jquery.backstretch.min.js"></script>

<script src="/Public/assets/apps/scripts/common.js"></script>
<link rel="gettext" type="application/x-po" href="/Lang/{$set_lan}/LC_MESSAGES/rokid_lang.po" />
<script src="/Public/assets/global/scripts/Gettext.js"></script>
<script src="/Public/assets/apps/scripts/login.js"></script>

</body>

</html>
