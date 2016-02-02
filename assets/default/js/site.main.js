var s = window.s || {};

s.dom = s.dom || {};
s.paths = {
	expressInstall: 'swf/expressInstall.swf',
	jPlayer: 'swf/jquery.jplayer-2.9.2.swf',
};
s.cssSelectors = {
	footer: "footer"
};

$(function(){
	s.proxy();
	s.setupPaths();
	s.cacheElements();
	
	s.menu.init();
//	s.counters.init();
	
	s.executeInitList(s.dom.$body);
});


