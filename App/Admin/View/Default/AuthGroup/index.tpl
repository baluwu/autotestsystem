<table id="authgroup"></table>

<div id="authgroup_tool" style="padding:5px;">
	<div style="margin-bottom:5px;">
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-add-new" onclick="authgroup_tool.add();">新增</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-edit-new" onclick="authgroup_tool.edit();">修改</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-delete-new" onclick="authgroup_tool.remove();">删除</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-reload" onclick="authgroup_tool.reload();">刷新</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" plain="true" iconCls="icon-redo" onclick="authgroup_tool.redo();">取消选定</a>
	</div>
</div>
<!--新增界面-->
<form id="authgroup_add" style="margin:0;padding:5px 0 0 25px;color:#333;">
	<p>角色名称：<input type="text" name="title" class="textbox" style="width:200px;"></p>
	<p>权限分配：<input type="text" id="auth_nav" name="rules" class="textbox" style="width:205px;"></p>
</form>
<!--修改界面-->
<form id="authgroup_edit" style="margin:0;padding:5px 0 0 25px;color:#333;">
    <p><input type="hidden" name="id_edit"></p>
	<p>角色名称：<input type="text" name="title_edit" class="textbox" disabled="true" style="width:200px;"></p>
	<p>权限分配：<input type="text" id="auth_edit_nav" name="rules_edit" class="textbox" style="width:205px;"></p>
</form>
<script type="text/javascript" src="__JS__/authgroup.js"></script>
