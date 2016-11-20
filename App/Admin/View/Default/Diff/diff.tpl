<!DOCTYPE html>
<head>
<title>Diff</title>
<link href="/Public/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/Public/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/Public/assets/global/css/components-md.css" rel="stylesheet" id="style_components" type="text/css"/>
<link href="/Public/assets/layout/css/layout.min.css" rel="stylesheet" type="text/css"/>
<link href="/Public/assets/apps/css/diffview.css" rel="stylesheet" type="text/css"/>
<style>
body { margin-top: 20px; }
.PropertyName { color: #CC004C; font-weight: 700; }
.String { color: #007777; font-weight: 400; }
.Boolean { color: #0000FF; font-weight: 400; }
.Number { color: #AA00AA; font-weight: 400; }
.container { position: relative; }
.checkbox {
    text-align: right;
    padding: 4px 10px 0;
    margin: 0;
    border-right: 1px solid #ddd;
    border-left: 1px solid #ddd;
}
.checkbox .mt-checkbox {
    padding-left: 18px;
    color: #666;
}
.hd {
    color: #fff;
    font-size: 18px;
    padding: 30px 16px !important;
}
.container {
    padding: 0 0 10px 0;
	margin: 0 auto;
    background: #fff;
    border-bottom-left-radius: 4px;
    border-bottom-right-radius: 4px;
    box-shadow: 0 2px 5px 2px rgba(22,44,66,.1);
}
input[type="checkbox"] {
    vertical-align: top;
    margin: 2px 2px 0 2px;
}
li { list-style: none; }
.list-group-item { font-weight: 700; }
.hidden { display: none; }
.dataTable tr td.diff-ctn { padding: 0; }
.dataTable tr td, .dataTable tr th {
    border-left: none !important; 
    border-right: none !important; 
    color: #333;
    cursor: pointer;
}
.diff { width: 100%; }
.diff-ctn { padding-bottom: 20px; }
.table-striped>tbody>tr.odd { background-color: #fbfcfd;    }
.table-striped>tbody>tr.even { background-color: #fff;    }
.author { display: none; }
.diff>thead>tr { display: none; }
.no-padding { padding: 0 !important; position: relative; }
.no-padding * {
    font-size: 12px;
}
table.diff tbody td { padding-top: 0; }
.container.hd { margin-top: 20px; }
.table thead tr th { font-weight: 400; }
.checkboxes { margin-left: -15px !important; }
.label:not(.md-skip) { font-weight: 400; border-radius: 10px; }
</style>
</head>
<script src="/Public/assets/global/plugins/jquery.min.js"></script>
<script src="/Public/assets/apps/scripts/common.js"></script>
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
    if (view.hasClass('hidden')) view.removeClass('hidden');
    else view.addClass('hidden');

    self = $(self);
    var s1 = self.attr('data-left-json');
    var s2 = self.attr('data-right-json');
    var t1 = self.attr('data-left-time');
    var t2 = self.attr('data-right-time');

    var b1 = beautyJson(s1);
    var b2 = beautyJson(s2);

    diffUsingJS(0, view.find('.diff-ctn').get(0), b1, b2, t1, t2);
}

$(function() {
    $('.exec-rs').each(function(i, el) {
        var self = $(this);

        var is_succ = self.attr('data-rs') == '1';

        if (is_succ) self.addClass('label-success').html(_et('Succeed'));
        else self.addClass('label-danger').html(_et('Fail'));
    });

    $('.delay-row').each(function(i, el) {
        if (0 == i % 2) $(el).addClass('odd'); 
        else $(el).addClass('even');
    });

    function aux_toggle_diff(ck_box) {
        var tr = ck_box.parent().next().find('tbody tr').not('.diff-row');

        if ('0' == ck_box.attr('value')) {
            ck_box.attr('value', '1');
            tr.hide();
        }
        else {
            ck_box.attr('value', '0');
            tr.show();
        }
    }

    $('.toggle-diff').click(function() {
        var ck_box = $(this);
        aux_toggle_diff(ck_box);
    });
})
</script>

<body>
<div class="container hd list-group-item active">
{$data.hd.left.task_name} <i class="fa  fa-arrows-h"></i> {$data.hd.right.task_name}
</div>
<div class="container">
<table class="table table-striped table-bordered table-hover table-checkable dataTable no-footer" role="grid" aria-describedby="">
<thead>
<tr role="row" class="heading">
	<th width="50%" align="left" class="sorting_disabled" rowspan="1" colspan="1" aria-label="<?php _e('Case');?>"><?php _e('Case'); ?></th>
	<th width="25%" class="sorting_disabled" rowspan="1" colspan="1" aria-label="{$data.hd.left.task_name}">{$data.hd.left.task_name} - {$data.hd.left.ver}</th>
	<th width="25%" class="sorting_disabled" rowspan="1" colspan="1" aria-label="{$data.hd.right.task_name}">{$data.hd.right.task_name} - {$data.hd.right.ver}</th>
</tr>
</thead>
<tbody>
    <foreach name="data.bd.left" item="it" key="sid">
	<tr role="row" class="delay-row" data-left-json='{$it.exec_content}' data-right-json='{$right_bd[$sid].exec_content}' class="list-group-item list-group-hd" onclick="toggleDetail(this, {$sid})"
            data-left-time="{$it.exec_start_time}" data-right-time="{$right_bd[$sid].exec_start_time}">
		<td><i class="fa fa-cube"></i>  {$it.path} </td>
        <td><span class="exec-rs label" data-rs="{$it.issuccess}"></span> </td>
        <td><span class="exec-rs label" data-rs="{$right_bd[$sid].issuccess}"></span> </td>
	</tr>
    <tr class="hidden">
        <td colspan="3" class="no-padding">
            <div class="col-sm-12 control-label checkbox">
                <label for="ck-box-{$sid}" class="toggle-diff" value="0" title="<?php _e('Distinct'); ?>"><i class="fa fa-exchange"></i></label>
            </div>
            <div class="diff-ctn"></div>
        </td> 
    </tr>
    </foreach>
</table>
</body>
</html>
