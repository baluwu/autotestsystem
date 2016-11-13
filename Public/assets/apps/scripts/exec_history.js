'use strict';

$(function() {
  $('.collapse').collapse();
  $('.panel-collapse').on('show.bs.collapse', function () {
    var dt = $(this).find('.exec-dt');
    dt.html(beautyJson(dt.attr('data-json')));
  })
});
