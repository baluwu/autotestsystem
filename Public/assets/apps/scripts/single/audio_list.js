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
      onSuccess: function (grid, response) {
        console.dir(arguments);
      },
      onError: function (grid) {
        console.dir(arguments);
      },
      onDataLoad: function (grid) {},
      loadingMessage: 'Loading...',
      dataTable: {
        bStateSave: true,
        lengthMenu: [
          [10, 20, 50, 100, 150, -1],
          [10, 20, 50, 100, 150, "All"]
        ],
        pageLength: 10,
        ajax: { url: '/Single/getAudioList' },
        keys: true,
        columns: [
          { data: 'id', orderable: false },
          { data: 'name', orderable: false },
          { data: 'when', orderable: false },
          { data: 'len', orderable: false },
          {
            class: "action-control",
            orderable: false,
            data: null,
            defaultContent: ""
          }
        ],
        order: [ [3, "desc"] ],
        columnDefs: [
          {
            render: function (data, type, row) {
              return formatSeconds(data);
            },
            targets: 3
          },
          {
            render: function (data, type, row) {
              return '<audio controls="true" src="' + data.path + '"></audio>';
            },
            targets: 4
          }
        ]
      }
    });
  };

  initAudioGrid();
});
