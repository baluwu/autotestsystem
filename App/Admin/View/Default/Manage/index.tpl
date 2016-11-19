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
  <title><?php _e('Users'); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/morris/morris.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/fullcalendar/fullcalendar.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="shortcut icon" href="/favicon.ico"/>
  <script>
    var CONFIG = {
      'ROOT': '__ROOT__',
      'MODULE': '__MODULE__',
      'INDEX': '{:U("Index/index")}',
    };
  </script>
  <style>
    .page-content-row .page-sidebar { width: 180px; min-width: 180px; }
    .popover-content .btn { padding: 5px 10px 2px 2px; margin-right: 0; }
    .input-sm { width: 120px; }
  </style>
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
                <li class="active"> <a href="/Manage/index"> <i class="fa fa-users"></i> <?php _e('User List'); ?> </a> </li>
                <li> <a href="/Manage/add"> <i class="fa fa-user-plus "></i> <?php _e('Add'); ?></a> </li>
              </ul>
              <h3>用户组管理</h3>
              <ul class="nav navbar-nav">
                <li> <a href="/Manage/group"> <i class="fa fa-plus "></i> <?php _e('User Group'); ?></a> </li>
              </ul>
            </nav>
          </div>
          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-users font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase"><?php _e('User List'); ?></span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li> <a href="/Index">Home</a> </li>
                        <li class="active"><?php _e('Users'); ?></li>
                      </ol>
                    </div>
                  </div>
                  <div class="portlet-body">
                    <div class="table-container">
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax" style="border: none;">
                        <thead>
                        <tr role="row" class="heading">
                          <th width="5%"> LDAP</th>
                          <th width="5%"> <?php _e('Account'); ?></th>
                          <th width="10%"> <?php _e('Name'); ?></th>
                          <th width="15%"> <?php _e('Email Address'); ?></th>
                          <th width="10%"> <?php _e('User Groups'); ?></th>
                          <th width="15%"> <?php _e('Found Time'); ?></th>
                          <th width="15%"> <?php _e('Last Visit Time'); ?></th>
                          <th width="10%"> <?php _e('Last Visit IP'); ?></th>
                          <th width="5%"> <?php _e('Start')} <i class="fa fa-info-circle tooltips" data-original-title="{:_e('’N’ is disable state'); ?>"></i></th>
                          <th width="5%"> <?php _e('Operate'); ?></th>
                        </tr>
                        <tr role="row" class="filter">
                          <td></td>
                          <td><input type="text" class="form-control form-filter input-sm" name="search_username" placeholder="<?php _e('Search By UserName'); ?>"></td>
                          <td><input type="text" class="form-control form-filter input-sm" name="search_name" placeholder="<?php _e('Search By Name'); ?>"></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>
                            <i class="fa fa-search filter-submit" style="visibility: hidden;"></i>
                            <i class="glyphicon glyphicon-ban-circle filter-cancel" style="visibility: hidden;"></i>
                          </td>
                        </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <include file="Public/footer"/>
  </div>
</div>
<!--[if lt IE 9]>
<script src="/Public/assets/global/plugins/respond.min.js"></script>
<script src="/Public/assets/global/plugins/excanvas.min.js"></script>
<![endif]-->
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/Public/assets/global/plugins/js.cookie.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/assets/global/plugins/jquery.blockui.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> 
<script src="/Public/assets/global/plugins/datatables/datatables.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/scripts/datatable.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/apps/scripts/common.js"></script>
<script src="/Public/assets/pages/scripts/ui-extended-modals.js"></script>
<script src="/Public/assets/apps/scripts/manage/index.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>
