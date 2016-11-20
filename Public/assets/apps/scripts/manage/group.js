/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var TableDatatablesAjax = function () {

    var initPickers = function () {
      //init date pickers
      $('.date-picker').datepicker({
        rtl: App.isRTL(),
        autoclose: true
      });
    };

    var handleRecords = function () {

      var grid = new Datatable();

      grid.init({
        src: $("#datatable_ajax"),
        onSuccess: function (grid, response) {
          // grid:        grid object
          // response:    json object of server side ajax response
          // execute some code after table records loaded
        },
        onError: function (grid) {
          // execute some code on network or other general error
        },
        onDataLoad: function (grid) {
          oTable=grid.getDataTable();
        },
        loadingMessage: 'Loading...',
        dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options

          // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
          // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js).
          // So when dropdowns used the scrollable div should be removed.
          //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

          "bStateSave": true, // save datatable state(pagination, sort, etc) in cookie.

          "lengthMenu": [
            [10, 20, 50, 100, 150, -1],
            [10, 20, 50, 100, 150, "All"] // change per page values here
          ],
          "pageLength": 20, // default record count per page
          "ajax": {
            "url": CONFIG['MODULE'] + '/Manage/getGroupList', // ajax source
          },
          keys: true,
          columns: [
            {
              data: 'id',
              orderable: false
            },
            {
              data: 'title',
              orderable: false
            },
            {
              data: 'auth',
              orderable: false
            },
            {
              class: "action-control",
              orderable: false,
              data: null,
              defaultContent: ""
            },
          ],
          "order": [
            [0, "asc"]
          ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return data?data:' '
              },
              "targets": 2
            },
            {
              "render": function (data, type, row) {
                return '<a href="./editgroup/id/' + data.id + '" title="项目授权"> <i class="fa fa-cubes font-light"></i></a>';
              },
              "targets": 3
            }
          ]
        }
      });
      var nEditing = null;
      var nNew = false;
      var oTable=grid.getDataTable();
      function restoreRow(oTable, nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);

        for (var i = 0, iLen = jqTds.length; i < iLen; i++) {
          oTable.fnUpdate(aData[i], nRow, i, false);
        }

        oTable.fnDraw();
      }

      function editRow(oTable, nRow) {
        var aData = oTable.fnGetData(nRow);
        var jqTds = $('>td', nRow);
        jqTds[0].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[0] + '">';
        jqTds[1].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[1] + '">';
        jqTds[2].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[2] + '">';
        jqTds[3].innerHTML = '<input type="text" class="form-control input-small" value="' + aData[3] + '">';
        jqTds[4].innerHTML = '<a class="edit" href="">Save</a>';
        jqTds[5].innerHTML = '<a class="cancel" href="">Cancel</a>';
      }

      function saveRow(oTable, nRow) {
        var jqInputs = $('input', nRow);
        oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
        oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
        oTable.fnUpdate('<a class="delete" href="">Delete</a>', nRow, 5, false);
        oTable.fnDraw();
      }

      function cancelEditRow(oTable, nRow) {
        var jqInputs = $('input', nRow);
        oTable.fnUpdate(jqInputs[0].value, nRow, 0, false);
        oTable.fnUpdate(jqInputs[1].value, nRow, 1, false);
        oTable.fnUpdate(jqInputs[2].value, nRow, 2, false);
        oTable.fnUpdate(jqInputs[3].value, nRow, 3, false);
        oTable.fnUpdate('<a class="edit" href="">Edit</a>', nRow, 4, false);
        oTable.fnDraw();
      }
      $('#addBtn').click(function (e) {
        e.preventDefault();

        if (nNew && nEditing) {
          if (confirm("Previose row not saved. Do you want to save it ?")) {
            saveRow(oTable, nEditing); // save
            $(nEditing).find("td:first").html("Untitled");
            nEditing = null;
            nNew = false;

          } else {
            oTable.fnDeleteRow(nEditing); // cancel
            nEditing = null;
            nNew = false;

            return;
          }
        }
        console.log(oTable);
        var aiNew = oTable.fnAddData(['', '', '', '', '', '']);
        var nRow = oTable.fnGetNodes(aiNew[0]);
        editRow(oTable, nRow);
        nEditing = nRow;
        nNew = true;
      });


      // handle group actionsubmit button click
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
          App.notification({
            type: 'danger',
            icon: 'warning',
            message: 'Please select an action',
            container: grid.getTableWrapper(),
            place: 'prepend'
          });
        } else if (grid.getSelectedRowsCount() === 0) {
          App.notification({
            type: 'danger',
            icon: 'warning',
            message: 'No record selected',
            container: grid.getTableWrapper(),
            place: 'prepend'
          });
        }
      });

      //grid.setAjaxParam("customActionType", "group_action");
      //grid.getDataTable().ajax.reload();
      //grid.clearAjaxParams();
    };

    return {

      //main function to initiate the module
      init: function () {

        initPickers();
        handleRecords();
      }

    };

  }();


  TableDatatablesAjax.init();
});
