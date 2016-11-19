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
  <title><?php _e('Case Record Show'); ?> | <?php _e('Case Groups'); ?> | <?php _e('Auto Test System'); ?> </title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/jsonFormater.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link rel="shortcut icon" href="/favicon.ico"/>
  <style>
  .portlet.light.portlet-fit > .portlet-body { padding: 10px 20px; }
  .control-label { margin: 5px 0; }
  .form-group { clear: both; }
  .case-name { display: inline-block; margin-right: 10px; }
  .collapsed .label { padding: 2px 5px; }
  .panel-default{ margin: 10px; }
  .PropertyName { color: #CC004C; font-weight: 700; }
  .String { color: #007777; font-weight: 400; }
  .Boolean { color: #0000FF; font-weight: 400; }
  .Number { color: #AA00AA; font-weight: 400; }
  .control-label {color: #666; overflow: hidden; }
  .mbadge { float: right; }
  </style>
  <script type="text/javascript" src="/Public/assets/apps/scripts/diff/beauty-json.js"></script>
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
            <div class="portlet mt-element-ribbon light portlet-fit bordered">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-object-group font-dark sbold"></i>
                    <span class="caption-subject uppercase font-dark sbold"><?php _e('Job Info'); ?></span>
                  </div>
                </div>
                <div class="portlet-body list">
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Name'); ?>"><i class="fa fa-object-group"></i>{$data.task_name}</p>
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Version'); ?>"><i class="fa fa-gg"></i>{$data.ver}</p>
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Belongs to the user'); ?>"><i class="fa fa-user"></i>{$data.nickname}<if condition="($data.nickname eq null) OR ($data.nickname eq '') ">{$data.nickname} </if></p>
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Create Time'); ?>"><i class="fa fa-calendar font-blue"></i>{$data.create_time|strtotime|date="Y-m-d H:i:s",###}</p>
                </div>
              </div>
              <h3>任务管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active"> <a href="/Task/index"> <i class="fa fa-object-ungroup"></i> <?php _e('List'); ?> </a> </li>
              </ul>
            </nav>
          </div>
          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">

                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-history font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase"> <?php _e('Job Record'); ?> </span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li> <a href="/Index">Home</a> </li>
                        <li> <a href="/Task/index"><?php _e('Jobs'); ?></a> </li>
                        <li class="active"><?php _e('Execution Record Show'); ?></li>
                      </ol>
                    </div>
                  </div>
                  <div class="portlet-body">
                      <div class="form-group">
                          <div class="col-sm-3 control-label"><?php _e('Name'); ?>: <code>{$data.task_name}</code></div>
                          <div class="col-sm-3 control-label"><?php _e('Version'); ?>: <code>{$data.ver}</code></div>
                          <div class="col-sm-3 control-label">IP Addr: <code>{$data.ip}</code></div>
                          <div class="col-sm-3 control-label">Port: <code>{$data.port}</code></div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-3 control-label"><?php _e('Create Time'); ?>: {$data.create_time}</div>
                          <div class="col-sm-3 control-label"><?php _e('Plan To performing'); ?>: {$data.exec_plan_time}</div>
                          <div class="col-sm-3 control-label"><?php _e('Start Time'); ?>: {$data.exec_start_time}</div>
                          <div class="col-sm-3 control-label"><?php _e('Finish Time'); ?>: {$data.exec_end_time}</div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-12 control-label"><?php _e('Note')}: <code><if condition="($data.description neq '')">{$data.description}<else />{:_e('Not'); ?></if></code></div>
                      </div>
                      <br />
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                          <foreach name="ExecHistory" item="d" key="i" >
                          <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="headingOne">
                                  <h4 class="panel-title">
                                      <a data-toggle="collapse" data-parent="#accordion" href="#collapse{$i}" aria-expanded="true" aria-controls="collapseOne">
                                      <i class="fa fa-cube"></i>
                                      <span class="case-name">{$d.name}</span>
                                      </a>
                                      <span class="mbadge label <if condition='($d.issuccess eq 1)'>label-success<else />label-danger</if>"><if condition="($d.issuccess eq 1)"><?php _e('Case Groups')}成功<else />{:_e('Case Groups'); ?>失败</if></span>
                                  </h4>
                              </div>
                              <div id="collapse{$i}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                  <div class="panel-body">
                                      <div class="form-group">
                                          <div class="col-sm-6 control-label"><?php _e('Path'); ?>: <code>{$d.path}</code></div>
                                          <div class="col-sm-6 control-label"><?php _e('Type'); ?>:
                                            <if condition="($d.nlp neq '')"><span class="label label-info">NLP</span><else />
                                            <span class="label label-primary">ASR</span>
                                            </if>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-sm-6 control-label"><?php _e('Start Time'); ?>: {$d.exec_start_time}</div>
                                          <div class="col-sm-6 control-label"><?php _e('Finish Time'); ?>: {$d.exec_end_time}</div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-sm-12 control-label"><if condition="$d.arc neq ''"><audio controls src="{$d.arc}"><else />nlp: <code>{$d.nlp}</code></if></div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-sm-12 control-label">
                                            <pre class="exec-dt" data-json='{$d.exec_content}'></script></pre>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          </foreach>
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
<script src="/Public/assets/apps/scripts/common.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/apps/scripts/group/excute_function.js"></script>
<script src="/Public/assets/global/scripts/JsonFormater.js"></script>
<script src="/Public/assets/apps/scripts/exec_history.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>
