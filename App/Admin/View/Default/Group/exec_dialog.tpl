<div id="exec" class="modal fade" tabindex="-1" data-focus-on="input:first">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title"><?php _e('Execution'); ?></h4>
  </div>
  <form action="#" class="form-horizontal form-row-seperated">
    <div class="modal-body">
      <div class="tips"></div>
        <input type="hidden" name="id" value=""/>
        <input type="hidden" name="type" value=""/>
        <div class="form-group form-md-line-input">
        <label class="col-md-3 control-label" for="ip">
          <span class="required">*</span>IP Address
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
        <label class="col-md-3 control-label" for="port">
          Port
        </label>
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
      <div class="form-group form-md-line-input form-interval">
        <label class="col-md-3 control-label" for="interval">
          Interval
        </label>
        <div class="col-md-8">
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
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn dark">Cancel</button>
        <button type="submit" class="btn btn-info exec_ok">Submit</button>
      </div>
    </div>
  </form>
</div>

