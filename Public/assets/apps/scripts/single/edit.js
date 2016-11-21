/**
 * Created by andy on 16/7/8.
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

  var validates = function () {

    var tpl = $("#validates_tpl").html();
    var count = $("#validates_table tbody tr").size();
    var add = function (tpl) {
      var currtr = $("#validates_table tbody").append(tpl).children('tr').last();
      currtr.find('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
      });
      currtr.find("input").val('');
      currtr.children('td').eq(0).text(++count);
    };
    var addEvet = function () {
      $("#validates_btn_add").on('click', function () {
        add(tpl);
      });
      $("#validates_table").on('click', "a.remove", function () {
        if ($("#validates_table tbody tr").size() > 1) {
          $(this).parents('tr').remove();
        }
      });
    };

    return {
      init: function () {
        addEvet();
      }
    };
  }();

  $("input[name='type_switch']").on('switchChange.bootstrapSwitch', function () {
    $("#arc_warp,#nlp_warp").toggle();
  });

  $('#atsform').validate({
    errorElement: 'span',
    errorClass: 'help-block help-block-error', 
    focusInvalid: false, 
    ignore: "",  
    messages: {
      select_multi: {
        maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
        minlength: jQuery.validator.format("At least {0} items must be selected")
      }
    },
    rules: {
      mc: {
        minlength: 2,
        maxlength: 100,
        required: true
      },
      property: {
        required: true
      },
      nlp: {
        required:"#type_switch:not(:checked)",
      },
      arc: {
        required:"#type_switch:checked",
      },
      'v1[]': {
        required: true,
        maxlength: 100
      },
      'v2[]': {
        required: true,
        maxlength: 20
      },
      'dept[]': {
        required: true
      }
    },

    invalidHandler: function (event, validator) { 
      App.scrollTo($('#atsform'), -200);
    },

    highlight: function (element) { 
      $(element).closest('.form-group').addClass('has-error'); 
    },

    unhighlight: function (element) { 
      $(element).closest('.form-group').removeClass('has-error');
    },

    success: function (label) {
      label.closest('.form-group').removeClass('has-error');
    },

    submitHandler: function (form) {
      $.ajax({
        url: '/Group/updateSingle',
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {},
        success: function (res, response, status) {
          if (res.error >= 0) {
            return App.ok('修改成功!');
          }

          App.warning( res.msg?res.msg:'未知错误！请检查内容后重新提交！', $(".page-content-col .portlet-title"));
        }
      });
      return false;
    }
  });

  $("#arc_upload").dropzone({
    url: "/Group/uploadLocalAudio",
    maxFilesize: 2,//单位MB
    uploadMultiple:false,
    dictInvalidFileType:'非法文件',
    dictDefaultMessage:'拖拽文件到此处',
    acceptedFiles:'audio/*',
    init: function () {},
    complete: function (file) {},
    error: function (file, message) {
      return App.warning(message);
    },
    success: function (file) {
      if(file.status!="success"){
        return App.warning('上传失败');
      }
      var res=JSON.parse(file.xhr.responseText);
      if (res.status==0){
        return App.warning(res.msg);
      }
      $('input[name="arc"]').val(res.path);

      $('.J_selected_audio').html('已选择: ' + res.name);
      $('#arc_upload').html(res.path);
    },
    addedfile: function (file) {}
  });
  
  
  validates.init();
  $('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
  });
  
  bindEvent();

  /*recorder*/
  var audio_context;
  var recorder;
  var formdata;

  function enableRecord() { 
      $('.icon-record').css('color', '#333').attr('data-status', '0');
      $('.record-btn').text('录音');
  }
  function disableRecord() {
      $('.icon-record').css('color', '#A00000').attr('data-status', '1');
      $('.record-btn').text('停止');
  }

  function startUserMedia(stream) {
      var input = audio_context.createMediaStreamSource(stream);
      console.log('Media stream created.');

      recorder = new Recorder(input);
      console.log('Recorder initialised.');

      enableRecord();
  }

  function startRecording() {
      recorder && recorder.record();
      disableRecord();
      console.log('Recording...');
  }

  function stopRecording() {
      recorder && recorder.stop();
      disableRecord();
      console.log('Stopped recording.');

      recorder && recorder.exportWAV(function(blob) {
          var url = URL.createObjectURL(blob);
          $('.use-audio').attr('data-url', url);
          $('#audio-player').attr('src', url);

          formdata = new FormData();
          formdata.append('wav', blob);
      });

      recorder && recorder.clear();
      enableRecord();
  }
  
  function uploadit() {
      if (!formdata) {
          return;
      }

      var item = $('#J_record_name'), name = item.val();
      if ($.trim(name) == '') {
         item.parents('.form-group').addClass('has-error');
         $('<span class="help-block help-block-error">This field is required.</span>').insertAfter('#J_record_name');
         return;
      }

      formdata.append('name', name);
      formdata.append('len', Math.floor($('#audio-player').get(0).duration || 0));

      $.ajax({
          url : "/Group/uploadRecordAudio/",
          type : 'POST',
          data : formdata,
          contentType : false,
          processData : false,
          success : function(data) {
              if (data.status == 1) {
                  $('#arc').val(data.path);
                  $('.J_selected_audio').html('已选择: ' + $('#J_record_name').val() + '.wav');
                  App.ok('已上传');
              }
              else App.warning(data.msg);
          },
          error : function() {
              App.warning('上传失败');
          }
      });
  }

  try {
      // webkit shim
      window.AudioContext = window.AudioContext
          || window.webkitAudioContext;
      navigator.getUserMedia = navigator.getUserMedia
          || navigator.webkitGetUserMedia
          || navigator.mozGetUserMedia
          || navigator.msGetUserMedia;

      window.URL = window.URL || window.webkitURL;

      audio_context = new AudioContext;
      console.log('Audio context set up.');
      console.log('navigator.getUserMedia ' + (navigator.getUserMedia ? 'available.' : 'not present!'));

  } catch (e) {
      alert('No web audio support in this browser!');
  }

  function bindEvent() {
      $('.J_asr_type_nav li').click(function() {
        $('.J_asr_type_nav li').removeClass('active');     
        $(this).addClass('active');

        var idx = $(this).attr('role-index');
        var tabs = $('.audio-item');
        tabs.not('[role-index=' + idx + ']').hide();
        tabs.eq(idx).show();
      });

      $('.icon-record').click(function() {
        var self = $(this), st = self.attr('data-status');
        if (st == '1') {
            return stopRecording();
        }
        startRecording();
      });

      $('.use-audio').click(uploadit);
      $('#J_record_name').blur(function() {
        var p = $(this).parents('.form-group');
        if ($.trim($(this).val()) != '') {
            p.removeClass('has-error');
            $(this).next().remove();
        }
      });
      $('.icon-play').click(function() {
        var player = $('#audio-player').get(0);

        var self = $(this);
        player.onplay = function() {
            self.css('color', '#A00000').attr('data-status', '1');
            $('.play-btn').text('暂停');
        }
        player.onended = player.onpause = function() {
            self.css('color', '#333').attr('data-status', '0');
            $('.play-btn').text('播放');
        }

        if (player.ended || player.paused) {
            player.play();

            var hd = setInterval(function() {
                var p = 0;
                var e = player.ended;
                if (e) {
                    p = 100;
                }
                else {
                    var t = player.duration;
                    var n = player.currentTime;
                    $('.J_eclipse_time').text(formatSeconds(n + 1));
                    p = ((n + 1) / t) * 100;
                    p = p > 100 ? 100 : p;
                }

                $('.progress-front').css('width', p + '%');    

                p == 100 && clearInterval(hd);
            }, 1000);
        }
        else { player.pause(); }
      });

      $('.re-record').click(function() {
        formdata = null;
        enableRecord();
      });
  }

  navigator.getUserMedia({
      audio : true
  }, startUserMedia, function(e) {
      console.log('No live audio input: ' + e + ', code = ' + e.code);
  });
  
  //执行，用户可以为此次执行添加注释，此时的执行，不记录结果到数据库，而是将结果返回到页面，这是一个阻塞的执行过程
  var $modal_exec = $('#exec');
  $('body').on('click', '#exec_btn', function () {
      $modal_exec.find('[name="id"]').val($('#id').val());
      $modal_exec.find('.tips').html("");
      $modal_exec.find('#ip').val(Cookies.get('IP') || '');
      $modal_exec.find('#port').val(Cookies.get('port') || '8080');

      $modal_exec.modal();
  });
   
   $("#exec form").validate({
        errorElement: 'span', 
        errorClass: 'help-block help-block-error', 
        focusInvalid: false, 
        ignore: "", 
        errorPlacement: function (error, element) {
          if (element.is(':checkbox')) {
            error.insertAfter(element.closest(".md-checkbox-list, .md-checkbox-inline, .checkbox-list, .checkbox-inline"));
          } else if (element.is(':radio')) {
            error.insertAfter(element.closest(".md-radio-list, .md-radio-inline, .radio-list,.radio-inline"));
          } else {
            error.insertAfter(element); 
          }
        },
        highlight: function (element) {
          $(element).closest('.form-group').addClass('has-error'); 
        },
        unhighlight: function (element) { 
          $(element).closest('.form-group').removeClass('has-error'); 
        },
        success: function (label) {
          label.closest('.form-group').removeClass('has-error'); 
        },
        rules: {
          ip: {
            minlength: 7,
            maxlength: 20,
            required: true
          },
          port: {
            minlength: 1,
            maxlength: 6,
            number: true
          }
        },
        submitHandler: function (form) {
          App.blockUI({
            message: '执行中....',
            target: $modal_exec,
            overlayColor: 'none',
            cenrerY: true,
            boxed: true
          });

          Cookies.set('IP', $modal_exec.find('#ip').val());
          Cookies.set('port', $modal_exec.find('#port').val());
          $.ajax({
            url: '/Group/ExecuteSingle',
            type: 'POST',
            dataType: 'JSON',
            data: $(form).serialize(),
            beforeSend: function () {},
            success: function (res, response, status) {
              $modal_exec.modal('hide');
              App.unblockUI($modal_exec);
              if (res.error < 0) {
                return App.warning( 'Excute fail, Error: ' + res.msg);
              }

              App.ok('执行成功');
            },
            error: function () {
              App.unblockUI($modal_exec);
            }
          });
          return false;
        }
      });
  });
