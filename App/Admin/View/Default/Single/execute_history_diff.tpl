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
  <title><?php _e('Contrast')} | {:_e('Case')} | {:_e('Auto Test System'); ?> </title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="/Public/assets/global/css/jsonFormater.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
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
              <!-- Brand and toggle get grouped for better mobile display -->
              <!-- Collect the nav links, forms, and other content for toggling -->
              <div class="portlet mt-element-ribbon light portlet-fit bordered">
                <div class="portlet-title">
                  <div class="caption">
                    <i class="fa fa-object-group font-green"></i>
                    <span class="caption-subject font-green bold uppercase"><?php _e('Case Info'); ?></span>
                  </div>
                </div>

                <div class="portlet-body list">
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Name'); ?>"><i class="fa fa-object-group"></i>{$single.name}</p>

                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Property')}"><i class="fa fa-gg"></i><if condition="($single.ispublic eq 1)">{:_e('Public')}<else />{:_e('Private'); ?></if></p>
                    <p class="margin-bottom-10 tooltips" data-original-title="NLP\ARC"><i class="fa fa-soundcloud"></i>{$single.nlp}
                      <if condition="$single.arc neq '' ">
                        <audio src='{$single.arc}' controls>
                      </if>
                    </p>
                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Belongs to the user'); ?>"><i class="fa fa-user"></i>{$single.nickname}<if condition="($single.nickname eq null) OR ($single.nickname eq '') ">{$group.nickname} </if></p>

                  <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Create Time'); ?>"><i class="fa fa-calendar font-blue"></i>
                    {$single.create_time|strtotime|date="Y-m-d",###}
                  </p>

                    <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Rule'); ?>"><i class="fa fa-cog font-blue"></i>
                        <foreach name="single.validates" item="v" >
                            <li style="list-style-type: none">{$v.v1}{$v.dept}{$v.v2}</li>
                        </foreach>
                    </p>
                  <p class="margin-bottom-10" ><i class="fa fa-rotate-left font-blue"></i>
                    <a href="javascript:void(0)" class="exec_btn" data-toggle="modal" data-title="{$single.name}" data-id="{$single.id}"><?php _e('Execution'); ?></a>
                  </p>

                </div>
              </div>


              <h3><?php _e('Case'); ?>用例管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active">
                  <a href="/Single/index">
                    <i class="fa fa-object-ungroup"></i> <?php _e('List'); ?> </a>
                </li>
                <li>
                  <a href="/Single/recycle">
                    <i class="fa fa-recycle "></i> <?php _e('Recycle Bin'); ?> </a>
                </li>
              </ul>
              <h3>Quick Actions</h3>
              <ul class="nav navbar-nav">
                <li>
                  <a href="/Single/add">
                    <i class="fa fa-plus "></i> <?php _e('Add'); ?></a>
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
                      <span class="caption-subject font-dark sbold uppercase"><?php _e('Case Record Contrast'); ?></span>
                    </div>
                    <!-- BEGIN BREADCRUMBS -->
                    <div class="breadcrumbs">

                      <ol class="breadcrumb">
                        <li>
                          <a href="/Index">Home</a>
                        </li>
                        <li>
                          <a href="/Single/index"><?php _e('Case'); ?></a>
                        </li>
                          <li>
                              <a href="/Single/execute_history/id/{:I('tid')}"><?php _e('Case Record'); ?></a>
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
                    <div class="row"  style="overflow: scroll">
                      <foreach name="execute_data" item="data" key="i" >
                        <div class="col-md-{:12/count($execute_data)}">
                          <table class="table table-bordered table-striped table-condensed flip-content">
                            <tbody>
                            <tr>
                              <td> IP </td>
                              <td> {$data.ip}  </td>
                            </tr>
                            <tr>
                              <td> Port </td>
                              <td> {$data.port} </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Execution Result'); ?> </td>
                              <td> <if condition="($data.status eq 2) "><?php _e('Succeed')}<else />{:_e('Fail'); ?> </if>  </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Create Time'); ?> </td>
                              <td> {$data.create_time} </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Start Time'); ?> </td>
                              <td> {$data.exec_start_time} </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Finish Time'); ?> </td>
                              <td> {$data.exec_end_time} </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Executioner'); ?></td>
                              <td> {$data.nickname}<if condition="($data.nickname eq null) OR ($data.nickname eq '') ">{$data.username} </if> </td>
                            </tr>
                            <tr>
                              <td> <?php _e('Case Record'); ?> </td>
                              <td>   <div class="code{$i}"></div> </td>
                            </tr>


                            </tbody>
                          </table>
                        </div>
                        <script>
                          codes.push({$data.exec_content});

                        </script>
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
    <h4 class="modal-title"><?php _e('Performing'); ?></h4>
  </div>
  <form action="#">
    <div class="modal-body">
      <div class="tips"></div>

      <input type="hidden" name="id" value=""/>


      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label">

        </label>

        <div class="col-md-10">
          <?php _e('Current Perform'); ?>:<span class="currName"></span>
        </div>
      </div>


      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>IP
        </label>

        <div class="col-md-10">
          <input type="text" class="form-control" id="ip" list="ip_data" placeholder="192.168.19.10" name="ip" required
                 data-tabindex="1">
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
          <div class="form-control-focus"></div>
        </div>
      </div>


      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="port">
          <span class="required"> * </span>Port
        </label>

        <div class="col-md-10">
          <input type="text" name="port" class="form-control" list="port_data" id="port" placeholder="8080"
                 data-tabindex="2">
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
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/scripts/JsonFormater.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Public/assets/apps/scripts/single/excute_function.js"></script>

<script>
  $(function () {
     $.each(codes, function (i) {
       $('div.code'+i).JsonFormater({
         isCollapsible: true,
         quoteKeys: true,
         tabSize: 1,
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


