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
  <title>执行记录对比 | 用例管理 | 自动化测试系统 </title>
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
    <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
          type="text/css"/>
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
    var codes = [];
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
              <div class="portlet mt-element-ribbon light portlet-fit bordered">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-object-group font-green"></i>
                    <span class="caption-subject font-green bold uppercase">用例组信息</span>
                  </div>
                </div>

                <div class="portlet-body list">
                  <p class="margin-bottom-10 tooltips" data-original-title="名称"><i
                      class="fa fa-object-group"></i>{$group.name}</p>

                  <p class="margin-bottom-10 tooltips" data-original-title="属性"><i class="fa fa-gg"></i>
                    <if condition="($group.ispublic eq 1)">公有
                      <else/>
                      私有
                    </if>
                  </p>

                  <p class="margin-bottom-10 tooltips" data-original-title="所属用户"><i
                      class="fa fa-user"></i>{$group.nickname}
                    <if condition="($group.nickname eq null) OR ($group.nickname eq '') ">{$group.nickname} </if>
                  </p>

                  <p class="margin-bottom-10 tooltips" data-original-title="创建时间"><i
                      class="fa fa-calendar font-blue"></i>{$group.create_time|strtotime|date="Y-m-d",###}</p>
                    <p class="margin-bottom-10" ><i class="fa fa-rotate-left font-blue"></i>

                        <a href="javascript:void(0)" class="exec_btn" data-toggle="modal" data-title="{$group.name}" data-id="{$group.id}">执行</a>
                    </p>
                </div>
              </div>

              <h3>用例管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active">
                  <a href="/Group/index">
                    <i class="fa fa-object-ungroup"></i> 用例列表 </a>
                </li>
                <li>
                  <a href="/Group/recycle">
                    <i class="fa fa-recycle "></i> 回收站 </a>
                </li>
              </ul>
              <h3>Quick Actions</h3>
              <ul class="nav navbar-nav">
                <li>
                  <a href="/Group/add">
                    <i class="fa fa-plus "></i> 添加用例</a>
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
                      <span class="caption-subject font-dark sbold uppercase">执行记录对比</span>
                    </div>
                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                      <ol class="breadcrumb">
                        <li>
                          <a href="/Index">Home</a>
                        </li>
                        <li>
                          <a href="/Group/index">用例管理</a>
                        </li>
                        <li>
                          <a href="/Group/execute_history/tid/{$group.id}">执行记录</a>
                        </li>
                        <li class="active">执行记录对比</li>
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
                    <div class="row" style="overflow: scroll">
                      <foreach name="execute_data" item="data" key="i">
                        <div class="col-md-{:12/count($execute_data)}">
                          <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
                            <tr>
                              <td> IP</td>
                              <td> {$data.ip}  </td>
                            </tr>
                            <tr>
                              <td> Port</td>
                              <td> {$data.port} </td>
                            </tr>
                            <tr>
                              <td> 执行结果</td>
                              <td>
                                <if condition="($data.status eq 2) ">成功
                                  <else/>
                                  失败
                                </if>
                              </td>
                            </tr>
                            <tr>
                              <td> 创建时间</td>
                              <td> {$data.create_time} </td>
                            </tr>
                            <tr>
                              <td> 开始执行时间</td>
                              <td> {$data.exec_start_time} </td>
                            </tr>
                            <tr>
                              <td> 执行完成时间</td>
                              <td> {$data.exec_end_time} </td>
                            </tr>
                            <tr>
                              <td> 执行人</td>
                              <td> {$data.nickname}
                                <if
                                  condition="($data.nickname eq null) OR ($data.nickname eq '') ">{$data.username} </if>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2"> 执行记录</td>
                            </tr>

                            <foreach name="data.exec" item="d" key="ii">
                              <tr>
                                <td colspan="2">
                                  <table class="table table-bordered table-striped table-condensed flip-content">
                                    <tr>
                                      <td>用例名称</td>
                                      <td>{$d.name}</td>
                                    </tr>
                                    <tr>
                                      <td>NLP/ASR</td>
                                      <td>{$d.nlp}
                                        <if condition="$d.arc neq '' ">
                                          <audio src='{$d.arc}' controls>
                                        </if>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>用例规则</td>
                                      <td>
                                        <volist name=":unserialize($d['validates'])" id="v">
                                          <li>{$v.v1}{$v.dept}{$v.v2}</li>
                                        </volist>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>执行成败</td>
                                      <td>
                                        <if condition="($d.issuccess eq 1) ">成功
                                          <else/>
                                          失败
                                        </if>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td>开始执行时间</td>
                                      <td> {$d.exec_start_time} </td>
                                    </tr>
                                    <tr>
                                      <td>执行完成时间</td>
                                      <td> {$d.exec_end_time}</td>
                                    </tr>
                                    <tr>
                                      <td>执行记录</td>
                                      <td>
                                        <div class="code{$i}-{$ii}"></div>
                                      </td>
                                    </tr>

                                  </table>

                                </td>
                              </tr>
                              <script>
                                codes[{$i}] = codes[{$i}] || [];
                                codes[{$i}][{$ii}] = codes[{$i}][{$ii}] || [];
                                codes[{$i}][{$ii}] ={$d.exec_content};
                              </script>
                            </foreach>


                            </tbody>
                          </table>
                        </div>

                      </foreach>
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
<div id="exec" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">用例组执行</h4>
    </div>
    <form action="#" >
        <div class="modal-body">
            <div class="tips"></div>

            <input type="hidden" name="id" value=""/>


            <div class="form-group form-md-line-input">
                <label class="col-md-2 control-label">

                </label>

                <div class="col-md-10">
                    当前执行用例组:<span class="currName"></span>
                </div>
            </div>


            <div class="form-group form-md-line-input">
                <label class="col-md-2 control-label" for="ip">
                    <span class="required">*</span>IP
                </label>
                <div class="col-md-10">
                    <input type="text" class="form-control" id="ip" list="ip_data" placeholder="192.168.19.10" name="ip" required data-tabindex="1">
                    <datalist id="ip_data">
                        <option value="192.168.19.10">
                        <option value="192.168.1.10">
                        <option value="192.168.2.10">
                        <option value="192.168.3.10">
                        <option value="192.168.4.10">
                        <option value="192.168.5.10">
                        <option value="192.168.6.10">
                        <option value="192.168.7.10">
                    </datalist>
                    <div class="form-control-focus"> </div>
                </div>
            </div>



            <div class="form-group form-md-line-input">
                <label class="col-md-2 control-label" for="port">
                    <span class="required"> * </span>Port
                </label>

                <div class="col-md-10">
                    <input type="text" name="port" class="form-control" id="port" list="port_data" placeholder="8080" data-tabindex="2">
                    <datalist id="port_data">
                        <option value="80">
                        <option value="8080">
                        <option value="8090">
                        <option value="8001">
                    </datalist>
                    <div class="form-control-focus"></div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
                <button type="submit" class="btn green exec_ok">Ok</button>
            </div>
        </div>
    </form>
</div>

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
<script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/apps/scripts/group/excute_function.js"></script>
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/scripts/JsonFormater.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>

<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script>
  $(function () {
    $.each(codes, function (i) {
      $.each(codes[i], function (ii) {
        $('div.code' + i + "-" + ii).JsonFormater({
          isCollapsible: true,
          quoteKeys: true,
          tabSize: 1,
          collapse: true,
          json: codes[i][ii]
        });
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


