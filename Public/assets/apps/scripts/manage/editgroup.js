/**
 * Created by andy on 16/7/8.
 */

function suggestPassword(passwd_form)
{
  var pwchars = "abcdefhjmnpqrstuvwxyz23456789ABCDEFGHJKLMNPQRSTUVWYXZ";
  var passwordlength = 16;    // do we want that to be dynamic?  no, keep it simple :)
  var passwd = passwd_form.generated_password;
  passwd.value = '';

  for (var i = 0; i < passwordlength; i++) {
    passwd.value += pwchars.charAt(Math.floor(Math.random() * pwchars.length));
  }
  passwd_form.password.value = passwd.value;
  passwd_form.repassword.value = passwd.value;
  return true;
}

jQuery(document).ready(function () {
  var setting = {
    async: {
      enable: true,
      url: '/Manage/getClassifyData/group_id/'+group_id,
    },
    check: {
      enable: true
    },
    data: {
      simpleData: {
        enable: true
      }
    },
    callback:{
        onClick: function() {
          $('#classify_str').val(getClassify());
        },
        onCheck: function() {
          $('#classify_str').val(getClassify());
        }
    }
  };

  $.fn.zTree.init($("#treeDemo"), setting);

  function getClassify() {
    var ck_t = $.fn.zTree.getZTreeObj("treeDemo").getCheckedNodes();
    var ck_t_id = [];
    $.each(ck_t, function(i){
      ck_t_id[ck_t_id.length] = ck_t[i]['id'];
    });
    var classify_str = ck_t_id.join(',');

    return classify_str;
  }

  $('#submit_save').click(function() {
    $.ajax({
      url: CONFIG['MODULE'] + '/Manage/saveGroupClassify',
      type: 'POST',
      data: {id: $('#id').val(), classify_str: getClassify()},
      beforeSend: function () {},
      success: function (data, response, status) {
        if (data >= 0) {
          return App.ok('修改成功');
        }
        App.warning( (data == -5)?'用户名称被占用':(data == 0)?'修改失败，请重试！':'未知错误！请重新刷新后提交！');
      }
    });
  });
});
