<!DOCTYPE html>
<head>
<title>Diff</title>
<link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
<link href="/Public/assets/apps/css/diffview.css" rel="stylesheet" type="text/css"/>
<style>
body { margin-top: 20px; }
.PropertyName { color: #CC004C; font-weight: 700; }
.String { color: #007777; font-weight: 400; }
.Boolean { color: #0000FF; font-weight: 400; }
.Number { color: #AA00AA; font-weight: 400; }
.container { position: relative; }
.ckbox {
    position: absolute;
    top: 5px; right: 22px;
    font-size: 12px;
    color: #666;
    font-weight: 400;
}
.hd {
    color: #fff;
    font-size: 18px;
    padding: 30px 16px !important;
}
.container {
    padding: 25px 0;
	margin: 0 auto;
    background: #fff;
}
input[type="checkbox"] {
    vertical-align: top;
    margin-top: 1px;
}
li { list-style: none; }
.list-group-item { font-weight: 700; }
</style>
</head>
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/beauty-json.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/diffview.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/difflib.js"></script>
<script>

function diffUsingJS(viewType, diffoutputdiv, left, right, l_title, r_title) {
	"use strict";
	var byId = function (id) { return document.getElementById(id); },
		base = difflib.stringAsLines(left),
		newtxt = difflib.stringAsLines(right),
		sm = new difflib.SequenceMatcher(base, newtxt),
		opcodes = sm.get_opcodes();

	diffoutputdiv.innerHTML = "";

	diffoutputdiv.appendChild(diffview.buildView({
		baseTextLines: base,
		newTextLines: newtxt,
		opcodes: opcodes,
		baseTextName: l_title,
		newTextName: r_title,
		//contextSize: contextSize,
		viewType: viewType
	}));
}

function toggleDetail(self, sid) {
  var view = $(self).next();
  view.toggle();

  self = $(self);
  var s1 = self.attr('data-left-json');
  var s2 = self.attr('data-right-json');
  var t1 = self.attr('data-left-time');
  var t2 = self.attr('data-right-time');
  
  var b1 = beautyJson(s1);
  var b2 = beautyJson(s2);
  
  diffUsingJS(0, view.get(0), b1, b2, t1, t2);
}

$(function() {
    $('#toggle-diff').click(function() {
        var t = $(this).attr('data-value');
        if (t == '1') {
            $('tr').not('.diff-row').hide();
            $(this).attr('data-value', '2');
        }
        else {
            $('tr').not('.diff-row').show();   
            $(this).attr('data-value', '1');
        }
    });   

    $('.exec-rs').each(function(i, el) {
        var self = $(this);

        var is_succ = self.attr('data-rs') == '1';

        if (is_succ) self.addClass('label-success').html('成功');
        else self.addClass('label-danger').html('失败');
    });
})
</script>

<body>
<div class="container hd list-group-item active">
    <i class="fa fa-tasks"></i> {$data.hd.left.task_name} &nbsp; <i class="fa fa-gg"></i> {$data.hd.left.ver}
    <!--
    <span class="badge">
        <i>{$data.hd.left.status}</i> / <i>{$data.hd.right.status}</i>
    </span>
    -->
</div>
<div class="container">
<ul class="list-group">
    <foreach name="data.bd.left" item="it" key="sid">
    <li>
        <div data-left-json='{$it.exec_content}' data-right-json='{$right_bd[$sid].exec_content}' class="list-group-item list-group-hd" onclick="toggleDetail(this, {$sid})"
            data-left-time="{$it.exec_start_time}" data-right-time="{$right_bd[$sid].exec_start_time}">
            <i class="fa fa-cube"></i>
            {$it.path}&nbsp;
            <span class="exec-rs label" data-rs="{$it.issuccess}"></span>
            <span class="exec-rs label" data-rs="{$right_bd[$sid].issuccess}"></span>
        </div>
        <div class="list-group-bd" class="J_diff_view" style="display: none"></div>
    </li>
    </foreach>
</ul>
<div class="ckbox"><input id="toggle-diff" type="checkbox" data-value="1" /><label for="toggle-diff">去相同项</label></div>
</body>
</html>
