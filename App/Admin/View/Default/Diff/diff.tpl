<!DOCTYPE html>
<style>
.PropertyName { color: #CC004C; font-weight: 700; }
.String { color: #007777; font-weight: 400; }
.Boolean { color: #0000FF; font-weight: 400; }
.Number { color: #AA00AA; font-weight: 400; }
.container { position: relative; }
.ckbox {
    position: absolute;
    top: 12px; right: -72px;
    font-size: 12px;
}
.container {
	width: 1024px;
	margin: 0 auto;
}
input[type="checkbox"] {
    vertical-align: top;
    margin-top: 1px;
}
</style>
<link href="/Public/assets/apps/css/diffview.css" rel="stylesheet" type="text/css"/>
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/beauty-json.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/diffview.js"></script>
<script type="text/javascript" src="/Public/assets/apps/scripts/diff/difflib.js"></script>
<script>
function diffUsingJS(viewType, diffoutputdiv, left, right) {
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
		baseTextName: "Left",
		newTextName: "Right",
		//contextSize: contextSize,
		viewType: viewType
	}));
}

function toggleDiff() {
    $('tr').not('.diff-row').toggle();   
}

window.onload = function() {
  var s1 = '{$left}';
  var s2 = '{$right}';

  var view = document.getElementById('J_diff_view');
  
  var b1 = beautyJson(s1);
  var b2 = beautyJson(s2);
  
  diffUsingJS(0, view, b1, b2);
}
</script>

<body>
<div class="container">
<div class="ckbox"><input id="toggle-diff" type="checkbox" onclick="toggleDiff()" /><label for="toggle-diff">去相同项</label></div>
<div id="J_diff_view">
</div>
</body>
</html>
