/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var TableDatatablesAjax = function () {

    var initPickers = function () {
      $('#datepicker').datepicker({
        rtl: App.isRTL(),
        orientation: "bottom auto",
        format: "yyyy-mm-dd",
        todayHighlight: true,
        autoclose: true
      });
    };

    var handleRecords = function () {

      var grid = new Datatable();

      grid.init({
        src: $("#datatable_ajax"),
        onError: function (grid) { },
        onDataLoad: function (grid) { },
        loadingMessage: 'Loading...',
        dataTable: {
          "bStateSave": true, 
          "lengthMenu": [
            [10, 20, 50, 100, 150, -1],
            [10, 20, 50, 100, 150, "All"] 
          ],
          "pageLength": 20, 
          "ajax": { "url": '/Task/getTasks' },
          keys: true,
          columns: [
            { data: 'id', orderable: false },
            { data: 'id', orderable: false },
            { data: 'task_name', orderable: false },
            { data: 'exec_start_time' },
            { data: 'ver', orderable: false },
            { data: 'description', orderable: false },
            { data: 'nickname', orderable: false },
            { class: "action-control", orderable: false, data: null, defaultContent: "" }
          ],
          "order": [ [3, "desc"] ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">' +
                  ' <input type="checkbox" class="checkboxes" value="'+data+'" />' +
                  '<span></span>' +
                  ' </label>';
              },
              "targets": 0
            },
            {
              "render": function (data, type, row) {
                return '<a title="'+_et('Check Result')+'" target="_blank" href="./execute_history_show/id/' + row.id + '" class=""> <i class="fa fa-history"></i></a>';
              },
              "targets": 7
            }
          ]
        }
      });

      grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
        e.preventDefault();
        var action = $(".table-group-action-input", grid.getTableWrapper());
        if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
          grid.setAjaxParam("customActionType", "group_action");
          grid.setAjaxParam("customActionName", action.val());
          grid.setAjaxParam("id", grid.getSelectedRows());
          grid.getDataTable().ajax.reload();
          grid.clearAjaxParams();
        } else if (action.val() == "") {
          App.warning( 'Please select an action', grid.getTableWrapper());
        } else if (grid.getSelectedRowsCount() === 0) {
          App.warning( 'No record selected', grid.getTableWrapper());
        }
      });

      $("#execute_diff").on('click', function () {
        if($('.checkboxes:checked').size()<2){
          App.warning( 'less then 2 records selected', grid.getTableWrapper());
          return false;
        }
        var _ids=[];
        $('.checkboxes:checked').each(function () {
            _ids.push($(this).val());
        });

        if (_ids.length > 2) {
          App.warning('You can only compare two results');
          return false;
        }
        $(this).attr('href','/diff/diff/left/' + _ids[0] + '/right/' + _ids[1]);
      });
    };

    return {
      init: function () {
        initPickers();
        handleRecords();
      }
    };
  }();

  TableDatatablesAjax.init();
});
