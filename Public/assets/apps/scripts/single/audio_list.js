/**
 * Created by W.G on 16/10/25.
 */
jQuery(document).ready(function () {
  function formatSeconds(a) { 
    var hh = parseInt(a/3600);
    if(hh<10) hh = "0" + hh;
    var mm = parseInt((a-hh*3600)/60);
    if(mm<10) mm = "0" + mm;
    var ss = parseInt((a-hh*3600)%60);
    if(ss<10) ss = "0" + ss;
    var length = hh + ":" + mm + ":" + ss;
    if(a>0){
        return length;
    }else{
        return "NaN";
    }
  }

  function initAudioGrid () {
    var grid = new Datatable();

    grid.init({
      src: $("#audio-grid"),
      onSuccess: function (grid, response) { },
      onError: function (grid) { },
      onDataLoad: function (grid) { 
        $('[data-toggle="confirmation"]').each(function () {
          $(this).confirmation();
          $(this).on('confirmed.bs.confirmation', function () {
            $.ajax({
              url: '/Group/removeAudio',
              type: 'POST',
              data: {
                id: $(this).data('id')
              },
              beforeSend: function () {},
              success: function (res, response, status) {
                if (!res.status) {
                  return App.warning( '删除失败' + res.msg, $(".page-content-col .portlet-title"));
                }

                App.ok('删除成功', $(".page-content-col .portlet-title"));

                grid.setAjaxParam("search_name", $('#search_name').val());
                grid.getDataTable().ajax.reload();
              }
            });
          });
        });

        $('.use_btn').on('click', function() {
          $('.J_selected_audio').html('已选择: ' + $(this).data('name'));
          $('#arc').val($(this).data('path'));
        });
      },
      loadingMessage: 'Loading...',
      dataTable: {
        bStateSave: true,
        lengthMenu: [
          [5, 10, 20],
          [5, 10, 20]
        ],
        pageLength: 5,
        ajax: { url: '/Group/getAudioList' },
        keys: true,
        columns: [
          { data: 'id', orderable: false },
          { data: 'name', orderable: false },
          { data: 'when', orderable: false },
          {
            class: "action-control",
            orderable: false,
            data: null,
            defaultContent: ""
          }
        ],
        order: [ [2, "desc"] ],
        columnDefs: [
            {
            render: function (data, type, row) {
              return [
                '<div class="audio-ctn"><audio controls="true" src="' + data.path + '" style="width: 160px"></audio></div>',
                '<a data-toggle="confirmation" data-id="' + data.id + '" data-btn-ok-label="OK" data-btn-ok-class="btn-info" data-btn-cancel-label="No" data-btn-cancel-class="btn-default" title="Are you sure to remove?"> <i class="glyphicon glyphicon-remove-circle"></i></a>',
                '<a title="Use audio" class="use_btn" data-id="' + data.id + '" data-name="' + data.name + '" data-path="' + data.path + '"><i class="glyphicon glyphicon-ok-circle"></i></a>'
              ].join('');
            },
            targets: 3
          }
        ]
      }
    });

    /*阻止回车事件冒泡*/
    $('#search_name').on('keydown', function(e) { 
      if (e.keyCode == 13) {
        return false;
      }
    });

    $('#search_name').on('keyup', function(e) {
      if (e.keyCode == 13) {
        grid.setAjaxParam("search_name", $('#search_name').val());
        grid.getDataTable().ajax.reload();
      }
      return false;
    });
  };

  initAudioGrid();
});
