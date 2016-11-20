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
  <title><?php _e('Case'); ?> | <?php _e('Auto Test System'); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
        type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet"
        type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
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
              <h3>用例管理</h3>
              <ul class="nav navbar-nav margin-bottom-35">
                <li class="active">
                  <a href="./index">
                    <i class="fa fa-object-ungroup"></i> <?php _e('List'); ?> </a>
                </li>
                <li>
                  <a href="./recycle">
                    <i class="fa fa-recycle "></i> <?php _e('Recycle Bin'); ?> </a>
                </li>
              </ul>
              <h3>Quick Actions</h3>
              <ul class="nav navbar-nav">
                <li>
                  <a href="./add">
                    <i class="fa fa-plus "></i> <?php _e('Add'); ?></a>
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
                          <th width="2%"> ID </th>
                          <th width="10%"> <?php _e('Name'); ?></th>
                          <th width="20%"> NLP/ASR</th>
                          <th width="7%"> <?php _e('Property'); ?></th>
                          <th width="20%"> <?php _e('Rule'); ?></th>
                          <th width="20%"> <?php _e('Create Time'); ?></th>
                          <td><?php _e('State'); ?> <i class="fa fa-info-circle tooltips" data-original-title="<?php _e('If The Use Case Is Being Performed, Please Wait Until It Is Completed Before Performing It Again.'); ?>"></i></td>
                          <th width="20%"> <?php _e('Operate'); ?></th>
                        </tr>

                        <tr role="row" class="filter">
                          <td></td>
                          <td>
                            <input type="text" class="form-control form-filter input-sm" name="search_single_name"
                                   placeholder="<?php _e('Search By Name'); ?>"></td>
                          <td>
                            <input type="text" class="form-control form-filter input-sm" name="search_single_nlp"
                                   placeholder="<?php _e('Search By NLP'); ?>">
                          </td>

                          <td>
                            <select name="search_single_type" class="form-control form-filter input-sm">
                              <option value="all"><?php _e('Property'); ?></option>
                              <option value="public"><?php _e('Public'); ?></option>
                              <option value="self"><?php _e('Private'); ?></option>

                            </select></td>
                          <td>
                          </td>
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


<div id="recycle" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
  <div class="modal-body">
    <p> <?php _e('It Cannot Be Recovered After Delete, Continue Or Not?'); ?> </p>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
    <button type="button" data-dismiss="modal" class="btn green">OK</button>
  </div>
</div>

<div id="exec" class="modal fade" tabindex="-1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php _e('Perform'); ?></h4>
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
 <script src="/Public/assets/global/plugins/bootstrap-growl/jquery.bootstrap-growl.min.js"></script> <!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script src="/Public/assets/global/plugins/datatables/datatables.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/scripts/datatable.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-modal/js/bootstrap-modal.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->


<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->

<!-- BEGIN PAGE LEVEL SCRIPTS -->

<script src="/Public/assets/pages/scripts/ui-extended-modals.js"></script>
<script src="/Public/assets/apps/scripts/single/index.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
</body>

</html>


