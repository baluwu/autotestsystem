/**
 * Created by andy on 16/7/8.
 */
jQuery(document).ready(function () {
  var beforeExpand = function(){
  }
  var newCount = 1;
  var className = "dark";

  var addHoverDom = function (treeId, treeNode) {
    var sObj = $("#" + treeNode.tId + "_span");

    if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
    //var addStr = "<span class='button add' id='addBtn_" + treeNode.tId
    //    + "' title='add node' onfocus='this.blur();'></span>";
    //sObj.after(addStr);
    var btn = $("#addBtn_"+treeNode.tId);
    if (btn) btn.bind("click", function(){
      var zTree = $.fn.zTree.getZTreeObj("treeDemo");
      zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
      $.get('/ManageGroupClassify/addNode', {'treeNode':{pId:treeNode.id, name:"new node" + (newCount++)}}, function(o){

      }, 'json');
      return false;
    });
  };

  var beforeRename = function (treeId, treeNode, newName, isCancel) {
    className = (className === "dark" ? "":"dark");
    if (newName.length == 0) {
      setTimeout(function() {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.cancelEditName();
        alert("节点名称不能为空.");
      }, 0);
      return false;
    }
    return true;
  }

  var onRename = function (e, treeId, treeNode, isCancel) {
    $.get('/ManageGroupClassify/editNode/id/' + treeNode.id + '/name/' + treeNode.name, {}, function(o){
      
    }, 'json');
  }

  var relId = 0;
  var onRemove = function (e, treeId, treeNode) {
    relId = treeNode.id;
    $('#J_del_alert').modal().show(); 
    return false;
  }

  $('body').on('click', '#J_do_del', function() {
    $.get('/ManageGroupClassify/delNode/id/'+relId, {}, function(o){ 
      window.location.reload();
    }, 'json');
  });

  var setting = {
    async: {
      enable: true,
      url: '/ManageGroupClassify/getAllNodes'
    },
    view: {
      addHoverDom: addHoverDom,
      selectedMulti: false
    },
    edit: {
      enable: true,
      editNameSelectAll: true
    },
    data: {
      simpleData: {
        enable: true
      }
    },
    callback: {
      beforeExpand: beforeExpand,
      //beforeRename: beforeRename,
      onRemove: onRemove,
      onRename: onRename
    }
  };

  $.fn.zTree.init($("#treeDemo"), setting);

  $('#J_add').click(function() {
    var v = $('#project').val().trim();

    if (!v) {
      return ;
    }

    $.get('/ManageGroupClassify/addNode/name/' + v, {}, function(o){
      window.location.reload();
    }, 'json');

  });
});
