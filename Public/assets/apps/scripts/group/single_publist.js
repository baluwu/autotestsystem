/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var TableDatatablesAjax = function () {

    var initPickers = function () {
      //init date pickers
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
        onSuccess: function (grid, response) {
          // grid:        grid object
          // response:    json object of server side ajax response
          // execute some code after table records loaded
        },
        onError: function (grid) {
          // execute some code on network or other general error
        },
        onDataLoad: function (grid) {

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
            "url": CONFIG['MODULE'] + '/Group/getSinglePubList/tid/'+CONFIG['tid'], // ajax source
          },
          keys: true,
          columns: [
            {
              data: 'id',
              orderable: false
            },
            {
              data: 'name',
              orderable: false
            },
            {
              data: 'nlp',
              orderable: false
            },
            {
              data: 'validates',
              orderable: false
            },
            {data: 'create_time'},
            {
              class: "action-control",
              orderable: false,
              data: null,
              defaultContent: ""
            }
          ],
          "order": [
            [4, "desc"]
          ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                return "<span title='"+row.name+"'>"+row.short_name+"</span>";
              },
              "targets": 1
            },
            {
              "render": function (data, type, row) {
                  if(data){
                    return "<span title='"+row.nlp+"'>"+row.short_nlp+"</span>";
                  }else{
                    return "<audio src='"+row.arc+"' controls>"  
                  }
              },
              "targets": 2
            },

            {
              "render": function (data, type, row) {
                var _str = '<div class="btn-group-red btn-group">\
                        <button data-close-others="true" data-hover="dropdown" data-toggle="dropdown" class="btn btn-sm md-skip dropdown-toggle" type="button">\
                            『' +data[0].v1+'』' + data[0].dept + '『'+ data[0].v2 + '』\
                        </button>\
                        <ul role="menu" class="dropdown-menu-v2">\
                        ';
                    for(var i in data){
                      _str += '<li><a href="javascript:;">『' +data[i].v1+'』' + data[i].dept + '『'+ data[i].v2 + '』</a></li>';  
                    }
                    _str+='</ul>\
                </div>';
                    return _str;
              },
              "targets": 3
            },

          ]
        }
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
