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
  <title>公共用例组 | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>  <!-- END PAGE LEVEL PLUGINS -->
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
      'id': '{$group.id}',
      'authGroup':{$admin_info.group_id}
    };
  </script>
  <style>
  .table-toolbar { margin: 10px 0 8px 10px; }
  </style>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo page-md">
<div class="wrapper">
  <include file="Public/header"/>
  <div class="container-fluid">
    <div class="page-content">
      <div class="page-content-container">
        <div class="page-content-row">
        <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-tasks font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase"><?php echo gettext('Task Execute Record');?></span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li>
                          <a href="/Index">Home</a>
                        </li>
                        <li class="active"><?php echo gettext('Task Execute Record');?></li>
                      </ol>
                      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".page-sidebar">
                        <span class="sr-only">Toggle navigation</span>
                            <span class="toggle-icon">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </span>
                      </button>
                    </div>
                  </div>
                  <div class="portlet-body">
                    <div class="table-container">
                      <div class="table-toolbar">
                        <div class="row">
                          <div class="col-md-6">
                            <div class="btn-group">
                              <a id="execute_diff" target="_blank" class="btn sbold green">
                                <i class="fa fa fa-th-list"></i> <?php echo gettext('Contrast');?>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                        <tr role="row" class="heading">
                          <th width="2%">
                            <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                              <input type="checkbox" class="group-checkable" data-set="#datatable_ajax .checkboxes"/>
                              <span></span>
                            </label>
                          </th>
                          <th width="5%"> ID</th>
                          <th width="15%"> {:_e('Task Name')}</th>
                          <th width="15%"> <?php echo gettext('Execute Time');?></th>
                          <th width="10%"> <?php echo gettext('Version Number');?></th>
                          <th width="20%"> <?php echo gettext('Note');?></th>
                          <th width="8%"> <?php echo gettext('Founder');?></th>
                          <th> <?php echo gettext('Operate');?></th>
                        </tr>
                        <tr role="row" class="filter">
                          <td></td>
                          <td></td>
                          <td> <input type="text" class="form-control form-filter input-sm" name="search_name" placeholder="按名称搜索"></td> <td>
                            <div class="input-daterange input-group" id="datepicker">
                              <input type="text" class="input-sm form-control form-filter" name="date_from" placeholder="From"/>
                              <span class="input-group-addon">to</span>
                              <input type="text" class="input-sm form-control form-filter" name="date_to" placeholder="To"/>
                            </div>
                          </td>
                          <td> <input type="text" class="form-control form-filter input-sm" name="search_ver" placeholder="按版本号搜索"> </td>
                          <td></td>
                          <td></td>
                          <td>
                            <a href="javascript:;"><i class="glyphicon glyphicon-search filter-submit" title="Search"></i></a>
                            <a href="javascript:;"><i class="glyphicon glyphicon-ban-circle filter-cancel" title="Reset"></i></a>
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
<link rel="gettext" type="application/x-po" href="/Lang/{$set_lan}/LC_MESSAGES/rokid_lang.po" />
<script src="/Public/assets/global/scripts/Gettext.js"></script>
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
<script src="/Public/assets/apps/scripts/task/tasks.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>
