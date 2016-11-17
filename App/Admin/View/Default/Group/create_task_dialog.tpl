<div id="J_task_single" class="modal modal-dialog" tabindex="-1" data-focus-on="input:first" style="width: 1000px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><i class="fa fa-tasks"></i> 创建任务</h4>
  </div>
  <form action="#" class="form-horizontal form-row-seperated" >
    <input type="hidden" id="J_single_ids" name="single_ids" />
    <div class="modal-body">
      <div class="tips"></div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>名称
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="name" placeholder="任务名称" name="name" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>执行时间
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="run_at" placeholder="2016-10-11 12:08:08" name="run_at" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span>版本号
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="ver" placeholder="1.0.1" name="ver" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> 注释 </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="description" placeholder="Description" name="description" data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> 通知邮箱 </label>
        <div class="col-md-9 input-group has-success" id="emailwarp">
            <input type="text" class="form-control" id="notify_email" name="notify_email" autocomplete="off" placeholder="Email Address"><span class="input-group-addon"><i class="fa fa-envelope"></i> </span>
            <div class="form-control-focus"></div>
        </div>
      </div>
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
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="port"> Port </label>
        <div class="col-md-9">
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
        <label class="col-md-2 control-label" for="interval"> Interval </label>
        <div class="col-md-9">
          <input type="text" name="interval" class="form-control" id="interval" list="interval_data" placeholder="1 Min" data-tabindex="3">
          <datalist id="interval_data">
            <option value="1">
            <option value="2">
            <option value="3">
            <option value="4">
            <option value="5">
          </datalist>
          <div class="form-control-focus"></div>
        </div>
      </div>
      <div class="modal-footer col-md-11">
            <!--<button type="button" class="btn btn-default J_view_cases">查看用例</button>-->
            <button type="button" data-dismiss="modal" class="btn btn-default">取消</button>
            <button type="submit" class="btn green exec_ok">确定</button>
        </div>
      </div>
      <!--
      <div class="col-md-12 case-list">
        <div class="col-md-12">
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
      -->
    </div>
  </form>
</div>


