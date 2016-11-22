<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
  <meta charset="utf-8"/>
  <title><?php _e('Case Groups'); ?> | <?php _e('Auto Test System'); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="/Public/assets/apps/css/ztree.css" type="text/css">
  <link rel="shortcut icon" href="/favicon.ico"/>
  <style>
  #datatable_ajax { border-top: none; }
  #datatable_ajax span.label-info, #datatable_ajax span.label-success { padding: 3px 20px; }
  #datepicker { width: 250px; }
  .form-horizontal .form-group.form-md-line-input .input-group {
      padding-left: 15px;
      padding-right: 15px; 
  }
  .col-sm-12 { padding-left: 30px; }
  .ztree { margin-top: 2px; padding: 5px 0 60px 0; }
  #J_add_project {
    margin: 8px 12px 0 0;
    float: right;
    font-size: 13px;
    font-weight: 700;
    z-index: 9999;
  }
  .case-list { display: none; }
  .tree-ctn { width: 300px; overflow: hidden;}
  .dropdown-menu > li > a { padding: 4px 18px; font-size: 12px; }
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
              <ul class="nav navbar-nav margin-bottom-35 tree-ctn">
                <li class="active">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" id="J_project_id" data-id="{$project_id}">
                            <i class="fa fa-cubes font-light"></i>
                            <span id="J_project_title">{$firstname}</span><span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu J_project_menu" role="menu">
                            <foreach name='projects' item='item'>
                            <li>
                            <a href="javascript:;" data-id="{$item.id}"> <i class="fa fa-cubes font-dark"></i> {$item.name}</a></li>
                            </foreach>
                        </ul>
                    </div>
                    <p class="navbar-text" id="J_add_project" title="<?php _e('Add'); ?>">
                        <a href="javascript:;" class="navbar-link"><i class="fa fa-plus"></i></a>
                    </p>
                    <ul id="J_ztree" class="ztree"></ul>
                </li>
                <li> <a href="javascript:;" class="J_add_task"> <i class="fa fa-tasks"></i> <?php _e('Create Job'); ?> </a> </li>
              </ul>
            </nav>
          </div>
          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-object-ungroup font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase"><?php _e('Case'); ?></span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li> <a href="/Index">Home</a> </li>
                        <li class="active"><?php _e('Case'); ?></li>
                      </ol>
                    </div>
                  </div>
                  <div class="portlet-body">
                    <div class="table-container">
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                        <tr role="row" class="heading">
                          <th width="5%"> ID </th>
                          <th width="25%"> <?php _e('Name'); ?></th>
                          <th width="15%"> <?php _e('Type'); ?></th>
                          <th width="20%"> <?php _e('Founder'); ?></th>
                          <th width="20%"> <?php _e('Create Time'); ?></th>
                          <th width="15%"> <?php _e('Operate'); ?></th>
                        </tr>
                        <tr role="row" class="filter">
                          <td></td>
                          <td> <input type="text" class="form-control form-filter input-sm input-name" name="search_single_name" placeholder="<?php _e('Search By Name'); ?>"></td>
                          <td>
                              <input type="hidden" class="form-filter" name="case_type" id="J_case_type" value="all" />
                              <div class="btn-group btn-group-xs">
                                  <button type="button" class="btn btn-default type-nlp">NLP</button>
                                  <button type="button" class="btn btn-default type-asr">ASR</button>
                              </div>
                          </td>
                          <td></td>
                          <td>
                            <div class="input-daterange input-group" id="datepicker">
                              <input type="text" class="input-sm form-control form-filter" name="date_from" placeholder="From"/>
                              <span class="input-group-addon">to</span>
                              <input type="text" class="input-sm form-control form-filter" name="date_to" placeholder="To"/>
                            </div>
                          </td>
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
<include file="Group/exec_dialog"/>
<include file="Group/create_task_dialog"/>
<include file="Group/create_project_dialog"/>
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
<script src="/Public/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/global/plugins/ztree/js/jquery.ztree.all-3.5.min.js"></script>
<script src="/Public/assets/apps/scripts/common.js"></script>
<link rel="gettext" type="application/x-po" href="/Lang/{$set_lan}/LC_MESSAGES/rokid_lang.po" />
<script src="/Public/assets/global/scripts/Gettext.js"></script>
<script src="/Public/assets/apps/scripts/group/index.js"></script>
<script src="/Public/assets/apps/scripts/group/casetree.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>
