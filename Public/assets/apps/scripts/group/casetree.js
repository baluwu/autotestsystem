/**
 * 树形结构
 * W.G Add
 */

'use strict';

$(function() {

  function getTree() { return $.fn.zTree.getZTreeObj("J_ztree"); }
  function getCheckedGroupId() {
    var ck_t = getTree().getCheckedNodes();
    var ck_t_id = [];
    $.each(ck_t, function(i, el){
      if (el.level==2 && el.group_id) {
        ck_t_id[ck_t_id.length] = el['group_id'];
      }
    });

    if (ck_t_id.length == 0) return '-1';
    return ck_t_id.join(',');
  }

  function reloadGrid(group_ids) {
    var project_id = $('#J_project_id').attr('data-id');
    grid.setAjaxParam('project_id', project_id);
    grid.setAjaxParam('group_ids', group_ids);
    $('.filter-submit').trigger('click');
  }

  function addHoverDom(treeId, treeNode) {
    var tid = treeNode.tId;
    var sObj = $("#" + tid + "_span");
    var showExecBtn = treeNode.level == 2;
    if (treeNode.editNameFlag || $("#addBtn_"+tid).length>0) return;

    var addStr = 
      "<span class='button add' id='addBtn_" + tid + "'></span>" + 
      "<span class='button remove' id='removeBtn_" + tid + "'></span>" +
      (showExecBtn ? "<span class='button exec' id='execBtn_" + tid + "'></span>" : '');
    
    sObj.after(addStr);

    $("#addBtn_"+tid).bind("click", function(){
      if (treeNode.level == 2) {
        window.open('/Group/add/group_id/' + treeNode.id, '_blank');
      }
      else addNode(treeNode);
      return false;
    });

    var cld = treeNode.children;

    var rmBtn = $('#removeBtn_' + tid);
    rmBtn.attr('title', '将删除当前节点及其子节点(包含用例), 继续么?');
    rmBtn.confirmation({
      placement: 'bottom',
      btnOkLabel: 'OK',
      btnCancelLabel: 'NO',
      onConfirm: function(e) {
        var cld = treeNode.children;
        if (cld && cld.length > 0) {
          $('#' + tid).find('.popover').hide();
          rmBtn.confirmation('destroy');
          return App.warning('子节点不为空, 无法删除');
        }

        var removed = false;
        $.ajax({
          url: '/ManageGroupClassify/delNode/id/'+treeNode.id,
          type: 'GET',
          dataType: 'JSON',
          async: false,
          success: function(r) {
            if (r.error) {
              return App.warning(r.msg || '未知错误');
            }
            removed = true;
          }
        });

        removed && getTree().removeNode(treeNode, false);
        $('#' + tid).find('.popover').hide();
        rmBtn.confirmation('destroy');
      },
      onCancel: function(e) {
        $('#' + tid).find('.popover').hide();
        rmBtn.confirmation('destroy');
      }
    });

    showExecBtn && $("#execBtn_"+tid).bind("click", function(){
      $('#exec').modal();
      $('#exec').on('shown.bs.modal', function () {
        var self = $(this);
        self.find('.modal-title').text('执行用例组 [' + treeNode.name + ']');
        self.find('[name="id"]').val(treeNode.id);
        self.find('[name="type"]').val('group');
        self.find('.tips').html("");

        self.find('#interval').val(Cookies.get('interval') || '1');
        self.find('.form-interval').show();
        self.find('#ip').val(Cookies.get('IP') || '');
        self.find('#port').val(Cookies.get('port') || '8080');
      })
      return false;
    });

    return true;
  }

  function removeHoverDom(treeId, treeNode) {
    var tid = treeNode.tId;
    $("#addBtn_"+tid).unbind().remove();
    $("#removeBtn_"+tid).unbind().remove();
    $("#execBtn_"+tid).unbind().remove();
  }

  function beforeRename(treeId, treeNode, newName, isCancel) {
    if (isCancel) return true;

    var ret = true;
    $.ajax({
      url: '/ManageGroupClassify/editNode/id/'+treeNode.id+'/name/'+newName,
      type: 'GET',
      dataType: 'JSON',
      async: false,
      success: function(r) {
        if (r.error) {
          ret = false;
          getTree().cancelEditName(treeNode.name);
          return App.warning(r.msg || '未知错误');
        }
      }
    });

    return ret;
  }
  
  var setting = {
      check: { enable: true },
      async: {
        enable: true,
        url: function() {
          return '/ManageGroupClassify/getProjectData/project_id/' + $('#J_project_id').attr('data-id');
        }
      },
      view: {
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
        dblClickExpand: false,
        selectedMulti: true
      },
      data: { simpleData: { enable: true, idKey: "id", pIdKey: "pid", rootPId: 0} },
      edit: { enable: true, editNameSelectAll: false, showRemoveBtn: false },
      callback:{
        beforeRename: beforeRename,
        onCheck: function(treeId, treeNode) {
          var gids = getCheckedGroupId();
          reloadGrid(gids);
        },
        beforeDbClick: function() { return false },
        beforeClick: function(treeId, treeNode) {
          //treeNode && getTree().checkNode(treeNode, !treeNode.checked, true, true);
          return false;
        }
      }
	};

  $('.J_add_task').click(function() {
    var $modal_exec = $('#J_task_single');
    var group_ids = getCheckedGroupId(); 

    $.ajax({
      'url': '/Group/getSingleByGroupId',
      'method': 'POST',
      'data': { group_ids: group_ids },
      'type': 'JSON',
      'success': function(r) {
        var body = [], single_ids = [];

        if (!r.data || !r.data.length) {
          return App.warning('没有选择任何用例, 无法创建任务!');
        }

        if (r.data.length > 500) {
          return App.warning('已超出任务用例数最大限制: 500');
        }

        $.each(r.data, function(i, el) {
          var tr = '<tr>';
          
          tr += '<td><input type="checkbox" checked class="single-ckbx" data-sid="' + el.id + '" /></td>' + '<td>' + el.id + '</td><td>' + el.name + '</td><td>' + el.nlp + el.arc + '</td>'
          tr += '</tr>';

          body.push(tr);
          single_ids.push(el.id);
        });
        
        $('#J_single_ids').val(single_ids.join(','));
        //$('#J_task_single_bd').html(body.join(''));

        $modal_exec.find('#interval').val(Cookies.get('interval') || '1')
        $modal_exec.find('#ip').val(Cookies.get('IP') || '');
        $modal_exec.find('#port').val(Cookies.get('port') || '8080');

        $modal_exec.modal({'width': 800});
      }
    });
  });

  $('#J_add_project').click(function() {
    $('#J_create_project').modal();
    $('body').on('click', '#J_create_project_ok', function() {
      var name = $('#J_project_name').val();
      if ('' == name) return App.warning('项目名称不能为空',$('#J_project_name'));
      $.get('/ManageGroupClassify/addNode/pid/0/name/'+name, {}, function(o){ 
        window.location.reload();
      }, 'json');
    });
  });

  function addNode(parentNode, dftName) {
    var pid = parentNode ? parentNode.id : 0;
    var lv = parentNode ? parentNode.level + 1 : 0;
    $.get('/ManageGroupClassify/addNode/pid/'+pid+'/lv/'+lv+'/name/'+(dftName || 'Node Name'), {}, function(o){ 
      if (o && !o.error) {
          getTree().addNodes(parentNode || null, {id: o.data, pid: pid, name: dftName || "Node Name"});
      }
      else App.warning(o.msg);
    }, 'json');
  }

  function loadTree() {
    $.fn.zTree.init($("#J_ztree"), setting);
    reloadGrid(0);
  }

  $('.J_project_menu a').click(function() {
    var project_id = $(this).attr('data-id'),
        projectObj = $('#J_project_id'),
        titleObj = $('#J_project_title');

    if (projectObj.attr('data-id') == project_id) return ;
    projectObj.attr('data-id', project_id);
    titleObj.text($(this).text());

    $.fn.zTree.destroy('J_ztree');

    loadTree();
  });

  loadTree();
});
