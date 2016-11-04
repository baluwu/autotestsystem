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
  <title>添加任务 | 用例组管理 | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet"
        type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="/Public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css" />
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN THEME GLOBAL STYLES -->
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <!-- END THEME GLOBAL STYLES -->


  <link href="/Public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
        type="text/css"/>


  <!-- BEGIN THEME LAYOUT STYLES -->
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <!-- END THEME LAYOUT STYLES -->
  <link rel="shortcut icon" href="/favicon.ico"/>
  <script>
    var CONFIG = {
      'ROOT': '__ROOT__',
      'MODULE': '__MODULE__',
      'INDEX': '{:U("Group/addTask")}',
    };
  </script>
</head>
<!-- END HEAD -->

<body class="page-header-fixed page-sidebar-closed-hide-logo page-md">
<!-- BEGIN CONTAINER -->
<div class="wrapper">
  <!-- BEGIN HEADER -->
  <include file="Public/header"/>
  <!-- END HEADER -->


  <div class="container-fluid">
    <div class="page-content">

      <!-- BEGIN SIDEBAR CONTENT LAYOUT -->
      <div class="page-content-container">
        <div class="page-content-row">
          <!-- BEGIN PAGE SIDEBAR -->
          <div class="page-sidebar">
            <nav class="navbar" role="navigation">
              <!-- Brand and toggle get grouped for better mobile display -->
              <!-- Collect the nav links, forms, and other content for toggling -->
              <h3>用例组管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li>
                  <a href="#">
                    <i class="fa fa-object-group"></i> 添加任务 </a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- END PAGE SIDEBAR -->
          <div class="page-content-col">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal form-row-seperated" action="./add" method="post" id="atsform">
                  <div class="portlet  light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-plus"></i> 添加任务
                      </div>
                      <!-- BEGIN BREADCRUMBS -->
                      <div class="breadcrumbs">

                        <ol class="breadcrumb">
                          <li>
                            <a href="/Index">Home</a>
                          </li>
                          <li>
                            <a href="./index">用例组管理</a>
                          </li>
                          <li class="active">添加任务</li>
                        </ol>
                        <!-- Sidebar Toggle Button -->
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".page-sidebar">
                          <span class="sr-only">Toggle navigation</span>
                            <span class="toggle-icon">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </span>
                        </button>
                        <!-- Sidebar Toggle Button -->
                      </div>
                      <!-- END BREADCRUMBS -->
                      <div class="actions btn-set">
                        <a href="./index" class="btn dark btn-secondary-outline">
                          <i class="fa fa-angle-left"></i> Back</a>
                        <button type="reset" class="btn btn-secondary-outline">
                          <i class="fa fa-reply"></i> Reset
                        </button>
                        <button type="submit" name="submit" value="Save" class="btn btn-success">
                          <i class="fa fa-check"></i> Save & Return List
                        </button>
                      </div>
                    </div>
                    <div class="portlet-body">
                      <div class="tabbable-bordered">
                        <div class="tab-content">
                          <div class="tab-pane active" id="tab_general">
                            <div class="form-body">
                              <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label" for="name">
                                  <span class="required"> * </span>名称
                                </label>
                                <div class="col-md-10">
                                  <input type="text" name="name" class="form-control" maxlength="20" id="name" placeholder="名称">
                                  <div class="form-control-focus"></div>
                                </div>
                              </div>

                              <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label">
                                  <span class="required"> * </span>执行时间:
                                </label>
                                <div class="col-md-10">
                                    <input id="datepicker" type="text" placeholder="执行时间" name="run_at" class="form-control">
                                    <div class="form-control-focus"></div>
                                </div>
                             </div>

                             <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label">
                                  <span class="required"> * </span>版本号:
                                </label>
                                <div class="col-md-10">
                                    <input type="text" placeholder="版本号" name="ver" class="form-control">
                                    <div class="form-control-focus"></div>
                                </div>
                             </div>

                             <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label">
                                  <span class="required"> * </span>备注:
                                </label>
                                <div class="col-md-10">
                                    <input type="text" placeholder="备注" name="description" class="form-control">
                                    <div class="form-control-focus"></div>
                                </div>
                             </div>
                             <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label">用例选择</label>
                             </div>
                             <div class="form-group form-md-line-input">

                             </div>

                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- END PAGE BASE CONTENT -->
          </div>
        </div>
      </div>
      <!-- END SIDEBAR CONTENT LAYOUT -->
    </div>
    <!-- BEGIN FOOTER -->
    <include file="Public/footer"/>
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
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/plugins/dropzone/dropzone.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Public/assets/apps/scripts/group/add.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>


