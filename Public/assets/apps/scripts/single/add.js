/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {

  function warning(msg, ctn) {
    App.notification({
      type: 'danger',
      icon: 'warning',
      message: msg,
      container: ctn,
      place: 'prepend',
      align: 'center',
      closeInSeconds: 3
    });
  }

  function ok(msg, ctn) {
     App.notification({
      type: 'success',
      icon: 'success',
      message: msg,
      container: ctn,
      place: 'prepend',
      align: 'center',
      closeInSeconds: 3
    });
  }

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

  var validates= function () {

    var tpl=$("#validates_table tbody").html();
    var count=1;
    var add= function (tpl) {
      $("#validates_table tbody").append(tpl).children('tr').last().find('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
      });
      $("#validates_table tbody tr").last().children('td').eq(0).text(++count);
    };
    var addEvet= function () {
      $("#validates_btn_add").on('click', function () {
        add(tpl);
      });
      $("#validates_table").on('click',"a.remove", function () {
        if( $("#validates_table tbody tr").size()>1){
          $(this).parents('tr').remove();
        }

      });
    };

    return {
      //main function to initiate the module
      init: function () {
        addEvet();
      }
    };
  }();

  $("input[name='type_switch']").on('switchChange.bootstrapSwitch', function () {
    $("#arc_warp,#nlp_warp").toggle();
  });

  $('#atsform').validate({
    errorElement: 'span', //default input error message container
    errorClass: 'help-block help-block-error', // default input error message class
    focusInvalid: false, // do not focus the last invalid input
    ignore: "",  // validate all fields including form hidden input
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
        required: true,
      },
      nlp: {
        required:"#type_switch:not(:checked)",
      },
      arc: {
        required:"#type_switch:checked",
      },
      'v1[]': {
        required: true,
        maxlength: 100,
      },
      'v2[]': {
        required: true,
        maxlength: 20,
      },
      'dept[]': {
        required: true,
      },
    },

    invalidHandler: function (event, validator) { //display error alert on form submit
      App.scrollTo($('#atsform'), -200);
    },

    highlight: function (element) { // hightlight error inputs
      $(element)
        .closest('.form-group').addClass('has-error'); // set error class to the control group
    },

    unhighlight: function (element) { // revert the change done by hightlight
      $(element)
        .closest('.form-group').removeClass('has-error'); // set error class to the control group
    },

    success: function (label) {
      label
        .closest('.form-group').removeClass('has-error'); // set success class to the control group
    },

    submitHandler: function (form) {
      var form_temp=$(form).serializeArray();
      console.log(form_temp["submit"]);
      $.ajax({
        url: CONFIG['MODULE'] + '/Single/addSingle',
        type: 'POST',
        data: $(form).serialize(),
        beforeSend: function () {

        },
        success: function (res, response, status) {
          if (res.error >= 0) {
            location.href='/Single/index';
            return;
          }

          App.notification({
            type: 'danger',
            icon: 'warning',
            message: res.msg?res.msg:'未知错误！请检查内容后重新提交！',
            container:$(".page-content-col .portlet-title"),
            place: 'prepend',
            closeInSeconds:1500
          });

        }
      });
     return false;
    }
  });

  $("#arc_upload").dropzone({
    url: "/Single/uploadFile",
    maxFilesize: 1,//单位MB
    uploadMultiple:false,
    dictInvalidFileType:'非法文件',
    dictDefaultMessage:'拖拽文件到此处',
    acceptedFiles:'audio/*',
    init: function () {

    },
    complete: function (file) {
      //console.log(file);
    },

    error: function (file, message) {
      alert(message);
      return false;
    },
    success: function (file) {
      if(file.status!="success"){
        return warning('上传失败');
      }
      var res=JSON.parse(file.xhr.responseText);
      if (res.status==0){
        return warning(res.msg);
      }
      $('input[name="arc"]').val(res.path);
      $('#arc_upload').html(res.path);
      console.log(file.xhr.responseText);
      console.log(file);
    },
    addedfile: function (file) {
      console.log(file);
    }
  });

  validates.init();
  $('.bs-select').selectpicker({
    iconBase: 'fa',
    tickIcon: 'fa-check'
  });

  $('.J_asr_type_nav li').click(function() {
    $('.J_asr_type_nav li').removeClass('active');     
    $(this).addClass('active');
  });

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

      bindEvent();
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
          $('#J_download_link').attr('href', url).attr('download', ($('.J_record_name').val() || new Date().toISOString()) + '.wav');

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
      $('#J_download_link').attr('download', name + '.wav');

      $.ajax({
          url : "/single/uploadAsr",
          type : 'POST',
          data : formdata,
          contentType : false,
          processData : false,
          success : function(data) {
              $('#record-path').val(data.path);
              ok('已上传至' + data.path);
              $('.form-hd').show();
          },
          error : function() {
              warning('上传失败');
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
        else {
            player.pause();
        }
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
});
