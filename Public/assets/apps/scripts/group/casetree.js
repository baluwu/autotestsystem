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
      if (el.level==2) {
        ck_t_id[ck_t_id.length] = el['group_id'];
      }
    });

    return ck_t_id.join(',');
  }

  function reloadGrid(group_ids) {
    grid.setAjaxParam('group_ids', group_ids);
    grid.getDataTable().ajax.reload();
    grid.clearAjaxParams();
  }

  function addHoverDom(treeId, treeNode) {
    var tid = treeNode.tId;
    var sObj = $("#" + tid + "_span");
    if (treeNode.editNameFlag || $("#addBtn_"+tid).length>0) return;

    var addStr = 
      (treeNode.level == 2 ? "<span class='button exec' id='execBtn_" + tid + "'></span>" : '') +
      "<span class='button add' id='addBtn_" + tid + "'></span>";
    
    sObj.after(addStr);

    $("#addBtn_"+tid).bind("click", function(){
      if (treeNode.level == 2) {
        /*add single*/
        window.open('/Single/add/group_id/' + treeNode.id, '_BLANK');
      }
      else addNode(treeNode);
      return false;
    });


    treeNode.level == 2 && $("#execBtn_"+tid).bind("click", function(){
      $('#exec').modal();
      return false;
    });

    return true;
  }

  function removeHoverDom(treeId, treeNode) {
    var tid = treeNode.tId;
    $("#addBtn_"+tid).unbind().remove();
    $("#execBtn_"+tid).unbind().remove();
  }

  function onRename(event, treeId, treeNode) {
    $.get('/ManageGroupClassify/editNode/id/'+treeNode.id+'/name/'+treeNode.name, {}, function(o){}, 'json');
    return false;
  }

  function onRemove(event, treeId, treeNode) {
    $.get('/ManageGroupClassify/delNode/id/'+treeNode.id, {}, function(o) {}, 'json');
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
      edit: { enable: true, editNameSelectAll: true },
      callback:{
        onRename: onRename,
        onRemove: onRemove,
        onCheck: function(treeId, treeNode) {
          var gids = getCheckedGroupId();
          reloadGrid(gids);
        },
        beforeDbClick: function() { return false },
        beforeClick: function(treeId, treeNode) {
          getTree().checkNode(treeNode, !treeNode.checked, true, true);
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
        $modal_exec.modal({'width': 1024});
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
      if (o && o.data) {
          getTree().addNodes(parentNode || null, {id: o.data, pid: pid, name: dftName || "Node Name"});
      }
    }, 'json');
  }

  function loadTree() {
    var project_id = $('#J_project_id').attr('data-id');
    if (!project_id) return ;
    $.fn.zTree.init($("#J_ztree"), setting);
    //getTree().expandNode(zTree.getNodeByParam("id", project_id));
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
