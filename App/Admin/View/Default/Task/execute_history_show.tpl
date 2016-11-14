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
  <title>执行记录查看 | 任务组管理 | 自动化测试系统 </title>
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
                    <span class="caption-subject uppercase font-dark sbold">任务信息</span>
                  </div>
                </div>
                <div class="portlet-body list">
                  <p class="margin-bottom-10 tooltips" data-original-title="名称"><i class="fa fa-object-group"></i>{$data.task_name}</p>
                  <p class="margin-bottom-10 tooltips" data-original-title="版本"><i class="fa fa-gg"></i>{$data.ver}</p>
                  <p class="margin-bottom-10 tooltips" data-original-title="所属用户"><i class="fa fa-user"></i>{$data.nickname}<if condition="($data.nickname eq null) OR ($data.nickname eq '') ">{$data.nickname} </if></p>
                  <p class="margin-bottom-10 tooltips" data-original-title="创建时间"><i class="fa fa-calendar font-blue"></i>{$data.create_time|strtotime|date="Y-m-d",###}</p>
                </div>
              </div>
              <h3>任务管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active"> <a href="/Task/index"> <i class="fa fa-object-ungroup"></i> 任务列表 </a> </li>
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
                      <span class="caption-subject font-dark sbold uppercase"> 任务执行记录 </span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li> <a href="/Index">Home</a> </li>
                        <li> <a href="/Task/index">任务管理</a> </li>
                        <li class="active">执行记录查看</li>
                      </ol>
                    </div>
                  </div>
                  <div class="portlet-body">
                      <div class="form-group">
                          <div class="col-sm-3 control-label">任务名称: <code>{$data.task_name}</code></div>
                          <div class="col-sm-3 control-label">版本: <code>{$data.ver}</code></div>
                          <div class="col-sm-3 control-label">IP Addr: <code>{$data.ip}</code></div>
                          <div class="col-sm-3 control-label">Port: <code>{$data.port}</code></div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-3 control-label">创建时间: {$data.create_time}</div>
                          <div class="col-sm-3 control-label">计划执行时间: {$data.exec_plan_time}</div>
                          <div class="col-sm-3 control-label">开始时间: {$data.exec_start_time}</div>
                          <div class="col-sm-3 control-label">结束时间: {$data.exec_end_time}</div>
                      </div>
                      <div class="form-group">
                          <div class="col-sm-12 control-label">备注: <code><if condition="($data.description neq '')">{$data.description}<else />无</if></code></div>
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
                                      <span class="label <if condition='($d.issuccess eq 1)'>label-success<else />label-danger</if>"><if condition="($d.issuccess eq 1)">成功<else />失败</if></span>
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapse{$i}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                  <div class="panel-body">
                                      <div class="form-group">
                                          <div class="col-sm-6 control-label">路径: <code>{$d.path}</code></div>
                                          <div class="col-sm-6 control-label">类型: 
                                            <if condition="($d.nlp neq '')"><span class="label label-info">NLP</span><else />
                                            <span class="label label-primary">ASR</span>
                                            </if>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <div class="col-sm-6 control-label">开始时间: {$d.exec_start_time}</div>
                                          <div class="col-sm-6 control-label">结束时间: {$d.exec_end_time}</div>
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
