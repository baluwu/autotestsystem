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
  <title><?php _e('Add'); ?> | <?php _e('Case'); ?> | <?php _e('Auto Test System'); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/> 
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/jsonFormater.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
  <link href="/Public/assets/global/css/plugins-md.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/layout/css/custom.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/apps/css/single-add.css" rel="stylesheet" type="text/css"/>
  <link rel="shortcut icon" href="/favicon.ico"/>
  <style>
  .portlet.light.portlet-fit > .portlet-body { padding: 10px 20px; }
  .glyphicon { vertical-align: top; font-size: 12px; }
  .prev-next {
    display: inline-block;
  }
  .prev-next i { color: #666; }
  .exec-single { display: none; }
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
                <div class="portlet mt-element-ribbon light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-cube font-dark"></i>
                            <span class="caption-subject uppercase font-dark"><?php _e('Case Info'); ?></span>
                        </div>
                    </div>
                    <div class="portlet-body list">
                        <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Product'); ?>"><i class="fa fa-cubes"></i>{$project_name}</p>
                        <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Module'); ?>"><i class="fa fa-cube"></i>{$model_name}</p>
                        <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Case'); ?>"><i class="fa fa-object-group"></i>{$group_name}</p>
                        <p class="margin-bottom-10 tooltips" data-original-title="<?php _e('Founder'); ?>"><i class="fa fa-user"></i>{$user}</p>
                        <p class="margin-bottom-10 tooltips exec-single" data-original-title="<?php _e('Execution'); ?>"><i class="fa fa-chevron-circle-right"></i><a href="javascript:;" id="exec_btn"><?php _e('Perform'); ?></a></p>
                    </div>
                </div>
                <h3><?php _e('Job'); ?></h3>
                <ul class="nav navbar-nav margin-bottom-35">
                    <li class="active"> <a href="/Task/index"> <i class="fa fa-object-ungroup"></i> <?php _e('List'); ?> </a> </li>
                </ul>
            </nav>
          </div>

          <div class="page-content-col">
            <div class="row">
              <div class="col-md-12">
                <form class="form-horizontal form-row-seperated" action="./addSingle" method="post" id="atsform">
                  <input type="hidden" name="group_id" value="{$group_id}" />
                  <div class="portlet  light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                      <div class="caption">
                      <i class="fa fa-plus font-dark"></i>
                      <span class="caption-subject font-dark sbold uppercase"><?php _e('Add Case'); ?></span>
                      </div> 
                      <div class="breadcrumbs">
                        <ol class="breadcrumb">
                          <> <a href="/Index">Home</a> </>
                          <li> <a href="/Group/index"><?php _e('Case Groups'); ?></a> </li>
                          <li class="active"> <?php _e('Add Case'); ?></li>
                        </ol>
                      </div>
                    </div>
                    <div class="portlet-body">
                            <div class="form-body">
                              <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label" for="mc">
                                  <span class="required"> * </span><?php _e('Name'); ?>
                                </label>
                                <div class="col-md-9">
                                  <input type="text" name="mc" class="form-control" id="mc" placeholder="<?php _e('Name'); ?>">
                                  <div class="form-control-focus"></div>
                                </div>
                              </div>
                              <div class="form-group form-md-line-input">
                                <label class="col-md-2 control-label" for="type">
                                  <span class="required"> * </span><?php _e('Type'); ?>
                                </label>
                                <div class="col-md-9">
                                  <input type="checkbox" name="type_switch"  id="type_switch" class="make-switch"
                                    data-on-switch-change="type_switch_fn" data-on-color="info"
                                    data-off-color="success" data-on-text="ASR" data-off-text="NLP">
                                </div>
                              </div>
                              <div class="form-group form-md-line-input" id="nlp_warp">
                                <label class="col-md-2 control-label" for="nlp">
                                  <span class="required"> * </span>NLP
                                </label>
                                <div class="col-md-9">
                                  <input type="text" name="nlp" class="form-control" id="nlp" placeholder="nlp">
                                  <div class="form-control-focus"></div>
                                </div>
                              </div>
                              <div class="form-group " id="arc_warp" style="display: none">
                                <label class="col-md-2 control-label" for="ARC">
                                  <span class="required"> * </span>ASR
                                </label>
                                <div class="col-md-9 select-audio-ctn">
                                    <div class="selected-audio J_selected_audio"></div>
                                    <input type="hidden" name="arc" value="" id="arc" />
                                    <input type="hidden" name="groupid" value="{$group_id}" />
                                    <ul class="nav nav-tabs J_asr_type_nav" role="tablist">
                                        <li role="presentation" class="active" role-index="0"><a href="javascript:;"><?php _e('Voice Record'); ?></a></li>
                                        <li role="presentation" role-index="1"><a href="javascript:;"><?php _e('Upload'); ?></a></li>
                                        <li role="presentation" role-index="2"><a href="javascript:;"><?php _e('Voice Library'); ?></a></li>
                                    </ul>
                                    <ul class="nav">
                                    <li class="audio-item record-item">
                                        <div class="form-body record-form">
                                            <div class="form-group form-md-line-input">
                                                <label class="control-label col-md-1">
                                                    <span class="required"> * </span><?php _e('Name'); ?>
                                                </label>
                                                <div class="col-md-5">
                                                    <input type="text" name="record_name" class="form-control" id="J_record_name" placeholder="<?php _e('Name'); ?>">
                                                    <div class="form-control-focus"> </div>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <div class="progress-plh">&nbsp;</div>
                                                <div class="progress-back">
                                                    <div class="progress-front">&nbsp;</div>
                                                </div>
                                                <div class="play-time">
                                                    <div class="progress-plh">&nbsp;</div>
                                                    <div class="J_eclipse_time">00:00:00</div>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <div class="col-md-12 record-ctrl">
                                                    <span class="glyphicon glyphicon-record icon-record"></span>
                                                    <span class="glyphicon glyphicon-play-circle icon-play"></span>
                                                    <span class="record-btn"><?php _e('Record'); ?></span>
                                                    <span class="play-btn"><?php _e('Play'); ?></span>
                                                </div>
                                            </div>
                                            <div class="form-group form-md-line-input">
                                                <div class="progress-plh">&nbsp;</div>
                                                <div class="record-tools">
                                                    <button type="button" class="btn btn-primary use-audio"><?php _e('Save And Use'); ?></button>
                                                    <button type="button" class="btn btn-default re-record"><?php _e('Re-Record'); ?></button>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="audio-item upload-item">
                                        <div id="arc_upload" class="ats-dropzone" /> <?php _e('Drag Upload'); ?>(*.wav,*.mp3,*.amr)</if></div>
                                    </li>
                                    <li class="audio-item lib-item">
                                        <div class="form-group ">
                                            <div class="col-md-12">
                                                <table class="table table-bordered table-hover" id="audio-grid">
                                                    <thead>
                                                    <tr role="row" class="heading">
                                                        <th width="50">No</th>
                                                        <th width="250"><?php _e('Name'); ?></th>
                                                        <th width="220">Date</th>
                                                        <th>&nbsp;</th>
                                                    </tr>
                                                    <!--begin search bar-->
                                                    <tr role="row" class="filter">
                                                        <td></td>
                                                        <td>
                                                        <input type="text" class="form-control form-filter input-sm" name="search_name" id="search_name" placeholder="<?php _e('Search By Name'); ?>">
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </li>
                                </div>
                              </div>
                            </div>
                            <div class="form-group form-md-line-input">
                            <label class="col-md-2 control-label">
                            <span class="required"> * </span><?php _e('Verify'); ?>
                            </label>
                            <div class="col-md-9">
                            <div class="text-align-reverse margin-bottom-10">
                                <a id="validates_btn_add" href="javascript:;" class="btn red "><i class="fa fa-plus"></i> <?php _e('Add Rule'); ?> </a>
                            </div>
                            <table class="table table-bordered table-hover" id="validates_table">
                            <thead>
                                <tr role="row" class="heading">
                                <th width="2%"> №</th>
                                <th width="30%"> key</th>
                                <th width="8%"> condition</th>
                                <th width="30%"> value <i class="fa fa-info-circle tooltips" data-original-title="<?php _e('Case Sensitive, Numbers And Strings Are Not Strictly Distinguished，For Example:’XXX’!=’xxx’, 123=’123’'); ?>"></i></th>
                                <th width="10%"></th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td> 1 </td>
                                    <td>
                                        <div class="col-md-12">
                                        <div class="form-group form-md-line-input">
                                        <input type="text" class="form-control" name="v1[]" placeholder="<?php _e('For Example');?>: intent.slots.whois">
                                        <div class="form-control-focus"></div>
                                        </div>
                                        </div>
                                    </td>
                                    <td>
                                        <select name="dept[]" class="bs-select form-control">
                                            <option value="大于"><?php _e('Greater Than'); ?></option>
                                            <option value="小于"><?php _e('Less Than'); ?></option>
                                            <option value="包含"><?php _e('Contain'); ?></option>
                                            <option value="不包含"><?php _e('Dose Not Contain'); ?></option>
                                            <option value="等于" selected><?php _e('Equal'); ?></option>
                                            <option value="不等于"><?php _e('Unequal To'); ?></option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input">
                                                <input type="text" class="form-control" name="v2[]" placeholder="<?php _e('Who'); ?>">
                                                <div class="form-control-focus"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td> <a href="javascript:;" class="btn btn-default remove"><i class="fa fa-times"></i> Remove </a> </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>

                        <div class="form-actions btn-set">
                        <div class="row">
                          <div class="col-md-offset-2 col-md-9">
                            <a href="/Group/index" class="btn btn-secondary-outline">
                              <i class="fa fa-angle-left"></i> Back</a>
                            <button type="reset" class="btn btn-secondary-outline">
                              <i class="fa fa-reply"></i> Reset
                            </button>
                            <button type="submit" name="submit" value="Save" class="btn btn-success">
                              <i class="fa fa-check"></i> Save
                            </button>
                          </div>
                        </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <include file="Public/footer"/>
  </div>
</div>
<div id="exec" class="modal fade" tabindex="-1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php _e('Execution'); ?></h4>
  </div>
  <form action="#" class="form-horizontal form-row-seperated">
    <div class="modal-body">
      <div class="tips"></div>
      <input type="hidden" name="id" id="id" value=""/>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>IP
        </label>
        <div class="col-md-9">
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
          <div class="form-control-focus"></div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="port">
          <span class="required"> * </span>Port
        </label>
        <div class="col-md-9">
          <input type="text" name="port" class="form-control" list="port_data" id="port" placeholder="8080" data-tabindex="2">
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
<audio controls="" src="" id="audio-player" style="display: none; vertical-align: middle;"></audio>
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
<script src="/Public/assets/global/plugins/dropzone/dropzone.min.js"></script>
<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/datatables.min.js"></script>
<script src="/Public/assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="/Public/assets/global/scripts/datatable.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"></script>
<script src="/Public/assets/apps/scripts/common.js"></script>
<link rel="gettext" type="application/x-po" href="/Lang/{$set_lan}/LC_MESSAGES/rokid_lang.po" />
<script src="/Public/assets/global/scripts/Gettext.js"></script>
<script src="/Public/assets/apps/scripts/single/add.js"></script>
<script src="/Public/assets/apps/scripts/single/recorder.js"></script>
<script src="/Public/assets/apps/scripts/single/recorderWorker.js"></script>
<script src="/Public/assets/apps/scripts/single/audio_list.js"></script>
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
</body>
</html>
