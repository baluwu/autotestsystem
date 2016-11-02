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
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="/Public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
  <!-- END PAGE LEVEL PLUGINS -->
  <!-- BEGIN THEME GLOBAL STYLES -->
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <!-- END THEME GLOBAL STYLES -->


  <!-- BEGIN THEME LAYOUT STYLES -->
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="/Public/assets/global/plugins/ztree/css/zTreeStyle/zTreeStyle.css" type="text/css">
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
                    <i class="fa fa-plus "></i> 用户组管理</a>
                </li>
                <li>
                  <a href="/ManageGroupClassify/index">
                    <i class="fa fa-plus "></i> 用户组分类管理</a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- END PAGE SIDEBAR -->
          <div class="page-content-col">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
              <div class="col-md-12">

                <form class="form-horizontal form-row-seperated" action="" method="post" id="atsform">
                  <input type="hidden" name="id" value="{$user.id}"/>

                  <div class="portlet  light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-plus"></i> 编辑用户分组
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
                            <select class="form-control" name="groupid" readonly="readonly">
                              <volist name="auth_group" id="auth">
                                <if condition="$auth.id eq $user.group_id ">
                                  <option value="{$auth.id}" selected>{$auth.title}</option>
                                  <else/>
                                  <option value="{$auth.id}">{$auth.title}</option>
                                </if>
                              </volist>
                            </select>

                            <div class="form-control-focus"></div>
                          </div>
                        </div>


                      <div class="form-group form-md-line-input">
                        <label class="col-md-2 control-label" for="groupid">
                          用户组分类
                        </label>

                        <div class="col-md-4">
                          <ul id="treeDemo" class="ztree">
                        </div>
                      </div>

                      </div>


                      <div class="form-actions">
                        <div class="row">
                          <div class="col-md-offset-2 col-md-10">

                            <button id="submit_save" type="button" value="Save" class="btn btn-success">
                              <i class="fa fa-check"></i> Save & Return List
                            </button>
                            <a href="/Manage/index" class="btn dark btn-secondary-outline">
                              <i class="fa fa-angle-left"></i> Back</a>


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


