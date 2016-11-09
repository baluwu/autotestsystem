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
      if (!el.isParent) {
        ck_t_id[ck_t_id.length] = el['group_id'];
      }
    });
    return ck_t_id.join(',');
  }

  function reloadGrid(group_id) {
    grid.setAjaxParam("group_id", group_id);
    grid.getDataTable().ajax.reload();
    grid.clearAjaxParams();
  }

  function addHoverDom(treeId, treeNode) {
    if (treeNode.group_id || treeNode.level == 2) return true;
    var tid = treeNode.tId;
    var sObj = $("#" + tid + "_span");
    if (treeNode.editNameFlag || $("#addBtn_"+tid).length>0) return;

    var addStr = 
      "<span class='button add' id='addBtn_" + tid + "'></span>";
    
    sObj.after(addStr);

    $("#addBtn_"+tid).bind("click", function(){
      addNode(treeNode);
      return false;
    });

    /*
    $("#removeBtn_"+tid).bind("click", function(){
      $.get('/ManageGroupClassify/delNode/id/'+treeNode.id, {}, function(o) {}, 'json');
      return false;
    });

    $("#editBtn_"+tid).bind("click", function(){
      $.get('/ManageGroupClassify/editNode/id/'+treeNode.id+'/name/'+treeNode.name, {}, function(o){}, 'json');
      return false;
    });
    */

    return true;
  }

  function removeHoverDom(treeId, treeNode) {
    var tid = treeNode.tId;
    $("#addBtn_"+tid).unbind().remove();
    $("#editBtn_"+tid).unbind().remove();
    $("#removeBtn_"+tid).unbind().remove();
  }

  function onRename(event, treeId, treeNode) {
    $.get('/ManageGroupClassify/editNode/id/'+treeNode.id+'/name/'+treeNode.name, {}, function(o){}, 'json');
  }

  function onRemove(event, treeId, treeNode) {
    $.get('/ManageGroupClassify/delNode/id/'+treeNode.id, {}, function(o) {}, 'json');
  }

  var setting = {
      check: { enable: true },
      async: {
        enable: true,
        url: '/ManageGroupClassify/getData/group/1'
      },
      view: {
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
        dblClickExpand: true,
        selectedMulti: true
      },
      data: { simpleData: { enable: true, idKey: "id", pIdKey: "pid", rootPId: 0} },
      edit: { enable: true, editNameSelectAll: true },
      callback:{
        onRename: onRename,
        onRemove: onRemove,
        onCheck: function(treeId, treeNode) {
            var gids = getCheckedGroupId();
            reloadGrid(gids);
        },
        beforeClick: function(treeId, treeNode) {
          /*
          var zTree = $.fn.zTree.getZTreeObj("J_ztree");
          if (treeNode.isParent) {
            zTree.expandNode(treeNode);
          }
          return false;
          */
          return false;
        }
      }
	};

  $.fn.zTree.init($("#J_ztree"), setting);

  $('.J_add_task').click(function() {
    var $modal_exec = $('#J_task_single');
    var group_ids = getCheckedGroupId(); 

    $.ajax({
      'url': '/Group/getSingleByGroupId',
      'method': 'POST',
      'data': { group_ids: group_ids },
      'type': 'JSON',
      'success': function(r) {
        var body = [];

        if (!r.data || !r.data.length) {
          return App.warning('无用例');
        }

        if (r.data.length > 100) {
          return App.warning('已超出任务用例数最大限制: 100');
        }

        $.each(r.data, function(i, el) {
          var tr = '<tr>';
          
          tr += '<td><input type="checkbox" checked class="single-ckbx" data-sid="' + el.id + '" /></td>' + '<td>' + el.id + '</td><td>' + el.name + '</td><td>' + el.nlp + el.arc + '</td>'
          tr += '</tr>';

          body.push(tr);
        });

        $('#J_task_single_bd').html(body.join(''));
        var el = $(this);
        $modal_exec.find('.currName').text(el.data('title'));
        $modal_exec.find('[name="id"]').val(el.data('id'));
        $modal_exec.find('.tips').html("");
        $modal_exec.modal({'width': 1024});
      }
    });
  });

  $('#J_add_project').click(function() {
    addNode(0);
  });

  function addNode(parentNode, dftName) {
    var pid = parentNode ? parentNode.id : 0;
    $.get('/ManageGroupClassify/addNode/pid/'+pid+'/name/'+(dftName || 'Node Name'), {}, function(o){ 
      if (o && o.data) {
          getTree().addNodes(parentNode || null, {id: o.data, pid: pid, name: dftName || "Node Name"});
      }
    }, 'json');
  }
});
