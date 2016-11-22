<div id="J_create_project" class="modal fade" tabindex="-1" data-focus-on="input:first">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title"><?php _e('Create Product'); ?></h4>
</div>
<div class="modal-body">
  <div class="tips"></div>
  <div class="form-group form-md-line-input">
    <label class="col-md-2 control-label" for="port">
      <span class="required"> * </span><?php _e('Name'); ?>
    </label>

    <div class="col-md-10">
      <input type="text" name="project_name" class="form-control" id="J_project_name" placeholder="<?php _e('Name'); ?>" data-tabindex="1">
      <div class="form-control-focus"></div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-outline dark">Close</button>
    <button type="button" id="J_create_project_ok" class="btn green exec_ok">OK</button>
  </div>
</div>
</div>

