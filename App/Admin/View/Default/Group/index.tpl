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
  <title>用例组管理 | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>  <!-- END PAGE LEVEL PLUGINS -->
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
  <style>.col-md-8 { padding: 0 !important; }</style>
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
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active">
                    <a href="./index"> <i class="fa fa-object-group"></i> 用例组列表 </a>
                    <ul id="treeDemo" class="ztree" style="padding: 10px;"></ul>
                </li>
                <li> <a href="javascript:;" class="J_add_task"> <i class="fa fa-tasks"></i> 创建任务 </a> </li>
                <li> <a href="./recycle"> <i class="fa fa-recycle "></i> 回收站 </a> </li>
              </ul>
              <ul class="nav navbar-nav">
                <li>
                  <a href="/Single/add">
                    <i class="fa fa-plus "></i> 添加用例
                  </a>
                </li>
                <li>
                  <a href="./add">
                    <i class="fa fa-plus "></i> 添加用例组
                  </a>
                </li>
              </ul>
            </nav>
          </div>
          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <div class="portlet light portlet-fit portlet-datatable bordered">
                  <div class="portlet-title">
                    <div class="caption">
                      <i class="fa fa-object-group font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase">用例组管理</span>
                    </div>
                    <div class="breadcrumbs">
                      <ol class="breadcrumb">
                        <li>
                          <a href="/Index">Home</a>
                        </li>
                        <li class="active">用例组管理</li>
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
                      <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                        <thead>
                        <tr role="row" class="heading">
                          <th width="2%"> ID </th>
                          <th width="20%"> 名称</th>
                          <th width="5%"> 属性</th>
                          <th width="15%"> 创建时间</th>
                          <td>状态<i class="fa fa-info-circle tooltips" data-original-title="执行中的用例，请等待执行完成后在执行"></i></td>
                          <th width="40%"> 操作</th>
                        </tr>

                        <tr role="row" class="filter">
                          <td></td>
                          <td>
                            <input type="text" class="form-control form-filter input-sm" name="search_name"
                                   placeholder="按名称搜索"></td>


                          <td>
                            <select name="search_type" class="form-control form-filter input-sm">
                              <option value="all">属性</option>
                              <option value="public">公共</option>
                              <option value="self">私有</option>

                            </select></td>

                          <td>
                            <div class="input-daterange input-group" id="datepicker">
                              <input type="text" class="input-sm form-control form-filter" name="date_from" placeholder="From"/>
                              <span class="input-group-addon">to</span>
                              <input type="text" class="input-sm form-control form-filter" name="date_to" placeholder="To"/>
                            </div>
                          </td>
                          <td></td>
                          <td>
                            <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                              <i class="fa fa-search"></i> Search
                            </button>
                            <button class="btn btn-sm red btn-outline filter-cancel">
                              <i class="fa fa-times"></i> Reset
                            </button>
                          </td>
                        </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
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

<div id="J_task_single" class="modal modal-dialog" tabindex="-1" data-focus-on="input:first" style="width: 1000px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">创建任务</h4>
  </div>
  <form action="#" class="form-horizontal form-row-seperated" >
    <input type="hidden" id="J_single_ids" name="single_ids" />
    <div class="modal-body">
      <div class="tips"></div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>名称
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="name" placeholder="任务名称" name="name" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>执行时间
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="run_at" placeholder="2016-10-11 12:08:08" name="run_at" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>版本号
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="ver" placeholder="1.0.1" name="ver" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> 注释 </label>
        <div class="col-md-8">
          <input type="text" class="form-control" id="description" placeholder="Description" name="description" data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> 通知邮箱 </label>
        <div class="col-md-8 input-group has-success" id="emailwarp">
            <input type="text" class="form-control" id="notify_email" name="notify_email" autocomplete="off" placeholder="Email Address"><span class="input-group-addon"><i class="fa fa-envelope"></i> </span>
            <div class="form-control-focus"></div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>IP
        </label>
        <div class="col-md-8">
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
        <label class="col-md-2 control-label" for="port"> Port </label>
        <div class="col-md-8">
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
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="port"> 用例选择 </label>
        <div class="col-md-8">
            <div class="portlet-body">
              <div class="table-container">
                  <table class="table table-striped table-bordered table-hover table-checkable" id="J_task_singles">
                  <thead>
                  <tr role="row" class="heading">
                  <th>
                  <input type="checkbox" checked class="group-ckbx" />
                  </th>
                  <th width=5%"> ID </th>
                  <th width="45%"> 名称</th>
                  <th width="45%"> NLP/ASR</th>
                  </tr>
                  </thead>
                  <tbody id="J_task_single_bd"></tbody>
                  </table>
              </div>
            </div>
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
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/Public/assets/global/plugins/js.cookie.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/assets/global/plugins/jquery.blockui.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
 <script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> <!-- END CORE PLUGINS -->
<script src="/Public/assets/global/plugins/datatables/datatables.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/scripts/datatable.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="/Public/assets/global/plugins/ztree/js/jquery.ztree.core.js"></script>
<script type="text/javascript" src="/Public/assets/global/plugins/ztree/js/jquery.ztree.excheck.js"></script>
<script src="/Public/assets/apps/scripts/common.js"></script>
<script src="/Public/assets/apps/scripts/group/index.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>


