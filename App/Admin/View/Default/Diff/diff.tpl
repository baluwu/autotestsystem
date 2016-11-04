<!DOCTYPE html>
<link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
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
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/beauty-json.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/diffview.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/difflib.js"></script>
<script>

var data = JSON.parse('{$data_string}');

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

  var s1 = JSON.stringify(data['bd']['left'][sid]);
  var s2 = JSON.stringify(data['bd']['right'][sid]);
  var t1 = data['bd']['left'][sid]['exec_start_time'];
  var t2 = data['bd']['right'][sid]['exec_start_time'];
  
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
        }
    );   
})
</script>

<body>
<div class="container hd list-group-item active">任务名: {$data.hd.left.task_name}, 版本: {$data.hd.left.ver}
    <span class="badge">
        <i>{$data.hd.left.status}</i> / <i>{$data.hd.right.status}</i>
    </span>
</div>
<div class="container">
<ul class="list-group">
    <foreach name="data.bd.left" item="it" key="sid">
    <li>
        <div class="list-group-item list-group-hd" onclick="toggleDetail(this, {$sid})">{$it.path}
            <span class="badge"><i>{$it.issuccess}</i> / <i>{$right_bd[$sid].issuccess}</i></span></div>
        <div class="list-group-bd" class="J_diff_view" style="display: none"></div>
    </li>
    </foreach>
</ul>
<div class="ckbox"><input id="toggle-diff" type="checkbox" data-value="1" /><label for="toggle-diff">去相同项</label></div>
</body>
</html>
