<div id="J_task_single" class="modal modal-dialog" tabindex="-1" data-focus-on="input:first" style="width: 1000px;">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><i class="fa fa-tasks"></i> <?php _e('Create Product'); ?></h4>
  </div>
  <form action="#" class="form-horizontal form-row-seperated" >
    <input type="hidden" id="J_single_ids" name="single_ids" />
    <div class="modal-body">
      <div class="tips"></div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span><?php _e('Name'); ?>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="name" placeholder="<?php _e('Name'); ?>" name="name" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span><?php _e('Time'); ?>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="run_at" placeholder="2016-10-11 12:08:08" name="run_at" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip">
          <span class="required">*</span><?php _e('Version'); ?>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="ver" placeholder="1.0.1" name="ver" required data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> <?php _e('Note'); ?> </label>
        <div class="col-md-9">
          <input type="text" class="form-control" id="description" placeholder="Description" name="description" data-tabindex="1">
          <div class="form-control-focus"> </div>
        </div>
      </div>
      <div class="form-group form-md-line-input">
        <label class="col-md-2 control-label" for="ip"> <?php _e('Notification email'); ?> </label>
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
            <button type="button" data-dismiss="modal" class="btn btn-default"><?php _e('Cancel'); ?></button>
            <button type="submit" class="btn green exec_ok"><?php _e('Confirm'); ?></button>
        </div>
      </div>
    </div>
  </form>
</div>


