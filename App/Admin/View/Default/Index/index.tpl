<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->

<head>
  <meta charset="utf-8"/>
  <title>Dashboard | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css" />
  <!-- BEGIN GLOBAL MANDATORY STYLES -->
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->

  <!-- BEGIN THEME GLOBAL STYLES -->
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <!-- END THEME GLOBAL STYLES -->
  <!-- BEGIN THEME LAYOUT STYLES -->
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <!-- END THEME LAYOUT STYLES -->
  <link rel="shortcut icon" href="/favicon.ico"/>
  <script>
    var CONFIG = {
      'ROOT': '__ROOT__',
      'MODULE': '__MODULE__',
      'INDEX': '{:U("Index/index")}',
    };
  </script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-md">
<!-- BEGIN CONTAINER -->
<div class="wrapper">
  <!-- BEGIN HEADER -->
  <include file="Public/header" />

  <!-- END HEADER -->
  <div class="container-fluid">
    <div class="page-content">
      <!-- BEGIN BREADCRUMBS -->
      <div class="breadcrumbs">
        <h1>Dashboard</h1>
        <ol class="breadcrumb">
          <li>
            <a href="/Index">Home</a>
          </li>
          <li class="active">Dashboard</li>
        </ol>
      </div>
      <!-- END BREADCRUMBS -->
      <!-- BEGIN PAGE BASE CONTENT -->
      <!-- BEGIN DASHBOARD STATS 1-->
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <a class="dashboard-stat dashboard-stat-v2 blue" href="/Single/index">
            <div class="visual">
              <i class="fa fa-object-ungroup"></i>
            </div>
            <div class="details">
              <div class="number">
                <span data-counter="counterup">{$count.single}</span>
              </div>
              <div class="desc"> 我的用例</div>
            </div>
          </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <a class="dashboard-stat dashboard-stat-v2 red" href="/Group/index">
            <div class="visual">
              <i class="fa fa-object-group"></i>
            </div>
            <div class="details">
              <div class="number">
                <span data-counter="counterup" >{$count.group}</span>
              </div>
              <div class="desc"> 我的用例组</div>
            </div>
          </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <a class="dashboard-stat dashboard-stat-v2 green" href="/Single/pub">
            <div class="visual">
              <i class="fa fa-cube"></i>
            </div>
            <div class="details">
              <div class="number">
                <span data-counter="counterup" >{$count.singlePub}</span>
              </div>
              <div class="desc">公共用例</div>
            </div>
          </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <a class="dashboard-stat dashboard-stat-v2 purple" href="/Group/pub">
            <div class="visual">
              <i class="fa fa-cubes"></i>
            </div>
            <div class="details">
              <div class="number">
                <span data-counter="counterup" >{$count.groupPub}</span></div>
              <div class="desc">公共用例组</div>
            </div>
          </a>
        </div>
      </div>
      <div class="clearfix"></div>
      <!-- END DASHBOARD STATS 1-->

      <!-- END PAGE BASE CONTENT -->
    </div>
    <!-- BEGIN FOOTER -->
    <include file="Public/footer" />
    <!-- END FOOTER -->
  </div>
</div>
<!-- END CONTAINER -->

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
 <script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> <!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->

<script src="/Public/assets/global/plugins/counterup/jquery.waypoints.min.js"></script>
<script src="/Public/assets/global/plugins/counterup/jquery.counterup.min.js"></script>

<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>

