s = window.s || {};
s.dom = s.dom || {};

//s.cssSelectors = $.extend(s.cssSelectors, {
//	content: '#content'
//});

$(function(){
	s.ajax.init(s.dom.$body);
});