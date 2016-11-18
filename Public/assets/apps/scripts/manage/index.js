/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var gt = new Gettext({ 'domain' : 'rokid_lang' });
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
        onSuccess: function (grid, response) { },
        onError: function (grid) { },
        onDataLoad: function (grid) {
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
                    var jy = gt.gettext('Disable');
                    var jh = gt.gettext('Activate');
                    var sb = gt.gettext('Fail');
                    var cg = gt.gettext('Succeed');
                    App.notification({
                      type: 'danger',
                      icon: 'warning',
                      message: (act=="Remove"?jy:jh)+sb,
                      container: $(".page-content-col .portlet-title"),
                      place: 'prepend'
                    });
                    return;
                  }
                  App.notification({
                    type: 'success',
                    icon: 'success',
                    message: (act=="Remove"?jy:jh)+cg,
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
        dataTable: { 
          "bStateSave": true, 
          "lengthMenu": [
            [10, 20, 50, 100, 150, -1],
            [10, 20, 50, 100, 150, "All"] 
          ],
          "pageLength": 20, 
          "ajax": {
            "url": CONFIG['MODULE'] + '/Manage/getList',
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
                return ' <span class="label label-sm label-' + (data ? 'success' : 'info') + '">' + (data ? "Y" : "N") + '</span>'
              },
              "targets": 0
            },
            {
              "render": function (data, type, row) {
                return ' <img src="'+row.headImg+'" width="30">' + data
              },
              "targets": 1
            },
            {
              "render": function (data, type, row) {
                return ' <span class="label label-sm label-' + (data==0 ? 'success' : 'warning') + '">' + (data==0 ? "Y" : "N") + '</span>'
              },
              "targets": 8
            },
            {
              "render": function (data, type, row) {
                var jh = gt.gettext('Activate');
                var wfdl = gt.gettext('not login');
                var qrjh = gt.gettext('Activation Confirmation');
                  return (row.isrecovery==0? '<a   data-toggle="confirmation" data-action="Remove" data-id="' + data.id + '" data-title="'+wfdl+'" data-btn-ok-label="OK" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="NO" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger"><i class="fa fa-remove"></i></a>':'<a data-action="Restore"   data-toggle="confirmation" data-id="' + data.id + '" data-title="'+qrjh+'" data-btn-ok-label="OK" data-btn-ok-icon="icon-like" data-btn-ok-class="btn-success" data-btn-cancel-label="NO" data-btn-cancel-icon="icon-close" data-btn-cancel-class="btn-danger"><i class="fa fa-check"></i> '+jh+' </a>') +
                '<a href="./edit/id/' + data.id + '" class=""><i class="fa fa-edit"></i></a>'
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
    };

    return {
      init: function () {
        initPickers();
        handleRecords();
      }
    };

  }();

  $('[name="search_username"], [name="search_name"]').keyup(function (e) {
    var k = e.keyCode || event.keyCode;
    k == 13 && $('.filter-submit').trigger('click');
  });

  TableDatatablesAjax.init();
});
