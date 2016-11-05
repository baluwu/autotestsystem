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
  <title>执行记录查看 | 任务组管理 | 自动化测试系统 </title>
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
  <link href="/Public/assets/global/css/jsonFormater.css" rel="stylesheet" type="text/css"/>
    <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet"
          type="text/css"/>
    <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
  <!-- END PAGE LEVEL PLUGINS -->
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
    var codes=[];
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
            <div class="portlet mt-element-ribbon light portlet-fit bordered">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-object-group font-green"></i>
                    <span class="caption-subject font-green bold uppercase">任务信息</span>
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
                <li class="active">
                  <a href="/Task/index">
                    <i class="fa fa-object-ungroup"></i> 任务列表 </a>
                </li>
              </ul>
            </nav>
          </div>
          <!-- END PAGE SIDEBAR -->
          <div class="page-content-col">
            <!-- BEGIN PAGE BASE CONTENT -->
            <div class="row">
              <div class="col-md-12">
                <!-- Begin: life time stats -->
                <div class="portlet light portlet-fit portlet-datatable bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-history font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase">执行记录查看</span>
                    </div>
                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li>
                          <a href="/Index">Home</a>
                        </li>
                        <li>
                          <a href="/Task/index">任务管理</a>
                        </li>
                        <li class="active">执行记录查看</li>
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
                    <!-- BEGIN -->
                      <div class="form-body">

                        <div class="form-group">
                          <label class="control-label col-md-2">IP</label>
                          <div class="col-md-10">
                            <p class="form-control-static">{$data.ip} </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">Port</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> {$data.port} </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">执行结果</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> <if condition="($data.status eq 2) ">成功<else />失败 </if> </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">创建时间</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> {$data.create_time} </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">开始执行时间</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> {$data.exec_start_time} </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">执行完成时间</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> {$data.exec_end_time} </p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-2">执行人</label>
                          <div class="col-md-10">
                            <p class="form-control-static"> {$data.nickname}<if condition="($data.nickname eq null) OR ($data.nickname eq '') ">{$data.username} </if></p>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-2">执行记录</label>
                          <div class="col-md-10">
                            <div class="form-control-static">

                            </div>
                          </div>
                        </div>
                        <foreach name="ExecHistory" item="d" key="i" >
                          <div class="form-group">
                                <label class="control-label col-md-2">用例名称</label>
                                <div class="col-md-10">
                                    <p class="form-control-static">{$d.name} </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">NLP\ASR</label>
                                <div class="col-md-10">
                                    <p class="form-control-static">{$d.nlp}
                                        <if condition="$d.arc neq '' ">
                                            <audio src='{$d.arc}' controls>
                                        </if> </p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-2">用例规则</label>
                                <div class="col-md-10">
                                    <div class="form-control-static" >
                                        <volist name=":unserialize($d['validates'])" id="v">
                                            <li >{$v.v1}{$v.dept}{$v.v2}</li>
                                        </volist>
                                    </div>
                                </div>
                            </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">执行成败</label>
                            <div class="col-md-10">
                              <p class="form-control-static"> <if condition="($d.issuccess eq 1) ">成功<else />失败 </if> </p>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-2">开始执行时间</label>
                            <div class="col-md-10">
                              <p class="form-control-static"> {$d.exec_start_time} </p>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-2">执行完成时间</label>
                            <div class="col-md-10">
                              <p class="form-control-static"> {$d.exec_end_time} </p>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="control-label col-md-2">执行记录</label>
                            <div class="col-md-10">
                              <p class="form-control-static code{$i}">  </p>
                            </div>
                          </div>

                          <script>
                            codes[{$i}]={$d.exec_content};
                          </script>
                        </foreach>
                      </div>
                      <div class="form-actions">
                        <a href="/Task/index" class="btn default">返回</a>
                      </div>

                    <!-- END -->
                  </div>
                </div>
                <!-- End: life time stats -->
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
 <script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> <!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/plugins/datatables/datatables.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/scripts/datatable.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/apps/scripts/group/excute_function.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Public/assets/global/scripts/JsonFormater.js"></script>
<script>
  $(function () {
    $.each(codes, function (i) {
      $('p.code'+i).JsonFormater({
        isCollapsible: true,
        quoteKeys: true,
        tabSize: 1,
        collapse:true,
        json:codes[i]
      });
    });
  });
</script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>


