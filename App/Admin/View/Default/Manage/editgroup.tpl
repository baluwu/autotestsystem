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
  <title>编辑用户分组 | 用户管理 | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="/Public/assets/apps/css/ztree.css" type="text/css">
  <link rel="shortcut icon" href="/favicon.ico"/>
  <script>
    var CONFIG = {
      'ROOT': '__ROOT__',
      'MODULE': '__MODULE__',
      'INDEX': '{:U("Index/index")}',
    };
  </script>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-md">
<div class="wrapper">
  <include file="Public/header"/>
  <div class="container-fluid">
    <div class="page-content">
      <div class="page-content-container">
        <div class="page-content-row">
          <div class="page-sidebar">
            <nav class="navbar" role="navigation">
              <h3>用户管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li>
                  <a href="/Manage/index">
                    <i class="fa fa-users"></i> 用户列表 </a>
                </li>
                <li>
                  <a href="/Manage/add">
                    <i class="fa fa-user-plus "></i> 添加用户</a>
                </li>
              </ul>
              <h3>用户组管理</h3>
              <ul class="nav navbar-nav">
                <li class="active">
                  <a href="/Manage/group">
                    <i class="fa fa-plus "></i>用户组项目授权</a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">

                <form class="form-horizontal form-row-seperated" action="" method="post" id="atsform">
                  <input type="hidden" name="id" id="id" value="{$group_id}"/>
                  <input type="hidden" name="classify_str" id="classify_str" value=""/>

                  <div class="portlet  light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-plus"></i> 用户组分类授权
                      </div>
                      <!-- BEGIN BREADCRUMBS -->
                      <div class="breadcrumbs">

                        <ol class="breadcrumb">
                          <li>
                            <a href="/Index">Home</a>
                          </li>
                          <li>
                            <a href="/Manage/index">用户管理</a>
                          </li>
                          <li class="active">编辑用户</li>
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

                    </div>
                    <div class="portlet-body">
                        <div class="form-group form-md-line-input">
                          <label class="col-md-2 control-label" for="groupid">
                            用户组
                          </label>
                          <div class="col-md-4">
                            <span class="form-control">{$group_name}</span>
                             <div class="form-control-focus"></div>
                          </div>
                        </div>


                      <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label" for="groupid">
                          用例组分类
                        </label>

                        <div class="col-md-4">
                          <ul id="treeDemo" class="ztree">
                        </div>
                      </div>

                      </div>
                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-2 col-md-10">
                            <span id="submit_save" class="btn btn-success">
                              <i class="fa fa-check"></i> Save & Return List
                            </span>
                            <a href="/Manage/group" class="btn dark btn-secondary-outline"><i class="fa fa-angle-left"></i> Back</a>
                          </div>
                        </div>
                      </div>

                      <div class="form-actions">&nbsp;</div>
                    </div>
                  </div>
              </div>
              <BR />
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
<script src="/Public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"
       ></script>
<script src="/Public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/assets/global/plugins/jquery.blockui.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
 <script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> <!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/plugins/dropzone/dropzone.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="/Public/assets/global/scripts/emailAutoComplete.js"></script>
<script type="text/javascript" src="/Public/assets/global/plugins/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/Public/assets/global/plugins/ztree/js/jquery.ztree.excheck.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script>
  var group_id = {$group_id};
</script>
<script src="/Public/assets/apps/scripts/manage/editgroup.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>


