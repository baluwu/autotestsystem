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
  <title>编辑用例 | 用例管理 | 自动化测试系统</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <meta content="andy" name="author"/>
  <script src="/Public/assets/global/plugins/pace/pace.min.js"></script>
  <link href="/Public/assets/global/plugins/pace/themes/pace-theme-flash.css" rel="stylesheet" type="text/css"/>
  <!-- BEGIN GLOBAL MANDATORY STYLES -->

  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
  <!-- END GLOBAL MANDATORY STYLES -->
  <!-- BEGIN PAGE LEVEL PLUGINS -->
  <link href="/Public/assets/global/css/jsonFormater.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css"/>
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
      'ID': {$data.id},
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
                  <a href="/Single/index">
                    <i class="fa fa-object-ungroup"></i> 用例列表 </a>
                </li>
                <li>
                  <a href="/Single/recycle">
                    <i class="fa fa-recycle "></i> 回收站 </a>
                </li>
              </ul>
              <h3>Quick Actions</h3>
              <ul class="nav navbar-nav">
                <li>
                  <a href="/Single/add">
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
                <form class="form-horizontal form-row-seperated" method="post" action="#" id="atsform">


                  <div class="portlet  light portlet-fit portlet-datatable bordered">
                    <div class="portlet-title">
                      <div class="caption">
                        <i class="fa fa-plus"></i> 编辑用例
                      </div>
                      <!-- BEGIN BREADCRUMBS -->
                      <div class="breadcrumbs">

                        <ol class="breadcrumb">
                          <li>
                            <a href="/Index">Home</a>
                          </li>
                          <li>
                            <a href="/Single/index">用例管理</a>
                          </li>
                          <li class="active">编辑用例</li>
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
                      <div class="actions btn-set">
                        <a href="/Single/index" class="btn dark btn-secondary-outline">
                          <i class="fa fa-angle-left"></i> Back</a>
                        <button type="reset" class="btn btn-secondary-outline">
                          <i class="fa fa-reply"></i> Reset
                        </button>
                        <button type="submit" name="submit" value="Save" class="btn btn-success">
                          <i class="fa fa-check"></i> Save & Return List
                        </button>

                      </div>
                    </div>
                    <div class="portlet-body">


                      <div class="tab-content">
                        <div class="tab-pane active" id="tab_general">
                          <div class="form-body">
                            <div class="form-group form-md-line-input">
                              <label class="col-md-2 control-label" for="mc">
                                <span class="required"> * </span>名称
                              </label>

                              <div class="col-md-10">
                                <input type="text" name="mc" class="form-control" id="mc" placeholder="名称"
                                       value="{$data.name}">

                                <div class="form-control-focus"></div>
                              </div>
                            </div>


                            <div class="form-group form-md-line-input">
                              <label class="col-md-2 control-label">
                                <span class="required"> * </span>属性:
                              </label>

                              <div class="col-md-10">

                                <div class="md-radio-inline">
                                  <div class="md-radio">
                                    <input type="radio" id="property1" name="property" value="1" class="md-radiobtn"
                                    <if condition="($data.property eq 1)"> checked</if>
                                    >
                                    <label for="property1">
                                      <span></span>
                                      <span class="check"></span>
                                      <span class="box"></span> 公开 </label>
                                  </div>
                                  <div class="md-radio">
                                    <input type="radio" id="property2" name="property" value="0" class="md-radiobtn"
                                    <if condition="($data.property eq 0)"> checked</if>
                                    >
                                    <label for="property2">
                                      <span></span>
                                      <span class="check"></span>
                                      <span class="box"></span> 私有 </label>
                                  </div>

                                </div>

                              </div>
                            </div>

                              <div class="form-group form-md-line-input">
                                <label for="groupid" class="col-md-2 control-label">
                                  <span class="required" aria-required="true"> * </span>用例组
                                </label>
                                <div class="col-md-4">
                                  <select name="groupid" class="form-control" aria-invalid="false">
                                      <foreach name="group" item="g" >
                                          <?php
                                            if($gid && $gid == $g['id']) {
                                          ?>
                                          <option value="{$g.id}" selected="true">
                                          <?php    
                                            }else{
                                          ?>
                                          <option value="{$g.id}">
                                          <?php
                                            } 
                                            if($g['ispublic'] == 1){
                                              echo "[公共]";
                                            }else{
                                              echo "[私有]";
                                            }
                                          ?>
                                          {$g.name}
                                          </option>
                                      </foreach>                           
                                  </select>
                                  <div class="form-control-focus"></div>
                                </div>
                              </div>

                            <div class="form-group form-md-line-input">
                              <label class="col-md-2 control-label" for="type">
                                <span class="required"> * </span>类型
                              </label>

                              <div class="col-md-10">
                                <input type="checkbox" name="type_switch"  id="type_switch" class="make-switch"
                                <if condition="($data.arc neq '')"> checked</if>
                                data-on-switch-change="type_switch_fn" data-on-color="info"
                                data-off-color="success" data-on-text="ASR" data-off-text="NLP">
                              </div>
                            </div>

                            <div class="form-group form-md-line-input" id="nlp_warp"
                            <if condition="($data.arc neq '')"> style="display: none"</if>
                            >
                            <label class="col-md-2 control-label" for="nlp">
                              <span class="required"> * </span>NLP
                            </label>

                            <div class="col-md-10">
                              <input type="text" name="nlp" class="form-control" id="nlp" placeholder="nlp" value="{$data.nlp}">

                              <div class="form-control-focus"></div>
                            </div>
                          </div>


                          <div class="form-group " id="arc_warp"
                          <if condition="($data.arc eq '')"> style="display: none"</if>
                          >
                          <label class="col-md-2 control-label" for="arc_upload_box">
                            <span class="required"> * </span>ASR
                          </label>

                          <div class="col-md-10">
                            <input type="hidden" name="arc" value="{$data.arc}"/>

                            <div id="arc_upload" class="ats-dropzone"/>
                            <if condition="($data.arc eq '')"> 拖拽到此处上传(*.wav,*.mp3,*.amr)
                              <else/>{$data.arc}</if>
                          </div>

                        </div>
                      </div>
                      <div class="form-group ">
                        <label class="col-md-2 control-label">
                          <span class="required"> * </span>验证规则
                        </label>

                        <div class="col-md-10">
                          <div class="text-align-reverse margin-bottom-10">
                            <a id="validates_btn_add" href="javascript:;" class="btn red ">
                              <i class="fa fa-plus"></i> 添加规则 </a>
                          </div>

                          <table class="table table-bordered table-hover" id="validates_table">
                            <thead>
                            <tr role="row" class="heading">
                              <th width="2%"> №</th>
                              <th width="30%"> key </th>
                              <th width="8%"> condition</th>
                              <th width="30%"> value <i class="fa fa-info-circle tooltips" data-original-title="区分大小写,数字和字符串不做严格区分,如：'xxx' != 'XXX', 123 = '123' "></i></th>
                              <th width="10%"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <foreach name="data.validates" item="validate" key="i">
                              <tr>
                                <td>
                                  {$i+1}
                                </td>
                                <td>
                                  <div class="col-md-12">
                                    <div class="form-group form-md-line-input">
                                      <input type="text" class="form-control" name="v1[]"
                                             placeholder="如: intent.slots.whois" value="{$validate.v1}">

                                      <div class="form-control-focus"></div>
                                    </div>
                                  </div>

                                </td>
                                <td>
                                  <select name="dept[]" class="bs-select form-control">
                                    <option value="大于"
                                    <if condition="($validate.dept eq '大于')"> selected</if>
                                    >大于</option>
                                    <option value="小于"
                                    <if condition="($validate.dept eq '小于')"> selected</if>
                                    >小于</option>
                                    <option value="包含"
                                    <if condition="($validate.dept eq '包含')"> selected</if>
                                    >包含</option>
                                    <option value="不包含"
                                    <if condition="($validate.dept eq '不包含')"> selected</if>
                                    >不包含</option>
                                    <option value="等于"
                                    <if condition="($validate.dept eq '等于')"> selected</if>
                                    >等于</option>
                                    <option value="不等于"
                                    <if condition="($validate.dept eq '不等于')"> selected</if>
                                    >不等于</option>
                                  </select>

                                </td>
                                <td>
                                  <div class="col-md-12">
                                    <div class="form-group form-md-line-input">
                                      <input type="text" class="form-control" name="v2[]" placeholder="谁是"
                                             value="{$validate.v2}">

                                      <div class="form-control-focus"></div>
                                    </div>
                                  </div>

                                </td>

                                <td>
                                  <a href="javascript:;" class="btn btn-default btn-sm remove">
                                    <i class="fa fa-times"></i> Remove </a>

                                </td>
                              </tr>
                            </foreach>


                            </tbody>
                          </table>
                          <script id="validates_tpl" type="text/tmpl">
                            <tr>
                            <td>
                            1
                            </td>
                            <td>
                            <div class="col-md-12">
                            <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" name="v1[]" placeholder="如: intent.slots.whois">
                            <div class="form-control-focus"></div>
                            </div>
                            </div>

                            </td>
                            <td>
                            <select name="dept[]" class="bs-select form-control">
                            <option value="大于">大于</option>
                            <option value="小于">小于</option>
                            <option value="包含">包含</option>
                            <option value="不包含">不包含</option>
                            <option value="等于" selected>等于</option>
                            <option value="不等于">不等于</option>
                            </select>

                            </td>
                            <td>
                            <div class="col-md-12">
                            <div class="form-group form-md-line-input">
                            <input type="text" class="form-control" name="v2[]" placeholder="谁是">
                            <div class="form-control-focus"></div>
                            </div>
                            </div>

                            </td>
                            <td>
                            <a href="javascript:;" class="btn btn-default btn-sm remove">
                            <i class="fa fa-times"></i> Remove </a>
                            </td>
                            </tr>
                          </script>
                        </div>
                      </div>
					  
					  <div class="form-group">
                        <label class="col-md-2 control-label">
                          
                        </label>

                        <div>
						<if condition="$data.status eq '0' ">
                            <a data-toggle="modal" data-title="{$data.name}" data-id="{$data.id}" data-status="{$data.status}" id="exec_btn" href="javascript:;" class="btn green">
                              <i class="fa fa-rotate-left"></i> 执行 </a>
						<else />
							<a href="javascript:;" class="btn green">
                              <i class="fa fa-rotate-left"></i> 用例执行中 </a>
						</if>
                        </div>
                      </div>
					  <!--执行报告-->
					  <div class="form-group">
                        <label class="col-md-2 control-label">
                          执行报告：
                        </label>

                        <div class='col-md-10'>
						<p class="form-control-static code"></p>
                        </div>
                      </div>


                    </div>

                  </div>
              </div>
            </div>
          </div>
          </form>
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
    <h4 class="modal-title">用例执行</h4>
  </div>
  <form action="#">
    <div class="modal-body">
      <div class="tips"></div>

      <input type="hidden" name="id" value=""/>
	  <input type="hidden" name="exec_type" value="2"/>


      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label">

        </label>

        <div class="col-md-10">
          当前执行用例:<span class="currName"></span>
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
<script src="/Public/assets/global/plugins/dropzone/dropzone.min.js"></script>

<script src="/Public/assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script src="/Public/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN THEME GLOBAL SCRIPTS -->
<script src="/Public/assets/apps/scripts/common.js"></script>
<!-- END THEME GLOBAL SCRIPTS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="/Public/assets/apps/scripts/single/edit.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/layout/scripts/layout.js"></script>
<script src="/Public/assets/layout/scripts/quick-sidebar.js"></script>
<!-- END THEME LAYOUT SCRIPTS -->
<script src="/Public/assets/global/scripts/JsonFormater.js"></script>
<script>
<!--回头把这段代码加到指定的返回值-->
  $('p.code').JsonFormater({
    isCollapsible: true,
    quoteKeys: true,
    tabSize: 1,
    json:{"is_success":true,"msg":"任务成功","content":{"success":false,"errorCode":519,"finished":true,"activation":false,"asr":"v2AOZhXHN5PaTS819NcR","domain":"TQP8cBm2I9Dvnq2ZnN","content":{"code":100000,"text":"放歌这种事情要严肃一点说，叫做“播放音乐”，你试试~！"}}}  });

</script>

</body>

</html>


