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
          //data-toggle="confirmation"
          $('[data-toggle="confirmation"]').each(function () {
            $(this).confirmation();
            var act=$(this).data('action');
            $(this).on('confirmed.bs.confirmation', function () {
              $.ajax({
                url: CONFIG['MODULE'] + '/Manage/'+act,
                type: 'POST',
                data: {
                  ids: $(this).data('id')
                },
                beforeSend: function () {

                },
                success: function (data, response, status) {
                  if (data == 0) {
                    App.notification({
                      type: 'danger',
                      icon: 'warning',
                      message: (act=="Remove"?"禁用":"激活")+"失败",
                      container: $(".page-content-col .portlet-title"),
                      place: 'prepend'
                    });
                    return;
                  }
                  App.notification({
                    type: 'success',
                    icon: 'success',
                    message: (act=="Remove"?"禁用":"激活")+'禁用成功',
                    container: $(".page-content-col .portlet-title"),
                    place: 'prepend'
                  });
                  $('.filter-submit').trigger('click');
                }
              });
            });
          });
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
            "url": CONFIG['MODULE'] + '/Manage/getList', // ajax source
          },
          keys: true,
          columns: [
            {
              data: 'ldap_uid',
              orderable: false
            },
            {
              data: 'manager',
              orderable: false
            },
            {
              data: 'nickname',
              orderable: false
            },
            {
              data: 'email',
              class: "text-center",
              orderable: false
            },
            {
              data: 'group_name',
              orderable: false
            },
            {data: 'create_time'},
            {data: 'last_login'},
            {
              data: 'last_ip',
              orderable: false
            },
            {
              data: 'isrecovery',
              textCenter:true
            },
            {
              class: "action-control",
              orderable: false,
              data: null,
              defaultContent: ""
            },
          ],
          "order": [
            [6, "desc"]
          ],
          "columnDefs": [
            {
              "render": function (data, type, row) {
                //return data?data:' '
                return ' <span class="label label-sm label-' + (data ? 'success' : 'info') + '">' + (data ? "Y" : "N") + '</span>'
              },
              "targets": 0
            },
            {
              "render": function (data, type, row) {
                //tooltips" data-original-title="属性"
                return ' <img src="'+row.headImg+'" width="30">' + data
              },
              "targets": 1
            },
            {
              "render": function (data, type, row) {
                //return data?data:' '
                return ' <span class="label label-sm label-' + (data==0 ? 'success' : 'warning') + '">' + (data==0 ? "Y" : "N") + '</span>'
              },
              "targets": 8
            },
            {
              "render": function (data, type, row) {

                return '<a href="./edit/id/' + data.id + '" class="btn dark btn-sm btn-outline margin-bottom-5"> <i class="fa fa-edit"></i> 编辑 </a>'
                  +(row.isrecovery==0? '<a   data-toggle="confirmation" data-action="Remove" data-id="' + data.id + '" data-title="禁用后账号无法登陆" data-btn-ok-label="Continue" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Stoooop!" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger" class="btn red btn-sm btn-outline margin-bottom-5"> <i class="fa fa-remove"></i> 禁用 </a>':'<a data-action="Restore"   data-toggle="confirmation" data-id="' + data.id + '" data-title="确定激活该用户?" data-btn-ok-label="Continue" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="Stoooop!" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger" class="btn green btn-sm btn-outline margin-bottom-5"> <i class="fa fa-check"></i> 激活 </a>');

              },
              "targets": 9
            }
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
          App.alert({
            type: 'danger',
            icon: 'warning',
            message: 'Please select an action',
            container: grid.getTableWrapper(),
            place: 'prepend'
          });
        } else if (grid.getSelectedRowsCount() === 0) {
          App.alert({
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
