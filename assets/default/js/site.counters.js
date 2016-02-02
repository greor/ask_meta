var s = window.s || {};

s.counters = {};
s.counters._first = true;
s.counters.google = [];
s.counters.yandex = [];
s.counters.tns = [];
s.counters.hit = function(url, title, referer){
	if (this._first && url == s.startUrl) return;
	var _this = this;
	window.setTimeout(function(){
		var i, doc_title = document.title;
		title && (document.title = title);
		_this._hitGoogleAll(url, title, referer);
		for (i = 0; i < _this.yandex.length; i++)
		{
			_this._hitYandex(_this.yandex[i], url, title, referer);
		}
		for (i = 0; i < _this.tns.length; i++)
		{
			_this._hitTns(_this.tns[i], url, title, referer);
		}
		title && (document.title = doc_title);
	},0);
	this._first = false;
};
s.counters._hitGoogle = function(name, url, title, referer){
	try {
		if (window.ga)
		{
			window.ga(name+'.send', 'pageview', {
				'page': url,
				'title': title,
				'referrer': referer
			});
		}
	} catch (e) {;}
};
s.counters._hitGoogleAll = function(url, title, referer){
	try {
		if (window.ga)
		{
			var trackers = ga.getAll();
			for (var i=0; i<trackers.length; ++i) {
				var tracker = trackers[i];
				tracker.send('pageview', {
					'page': url,
					'title': title,
					'referrer': referer
				});
			}
		}
	} catch (e) {;}
};
s.counters._hitYandex = function(account, url, title, referer){
	try {
		if (window["yaCounter"+account])
		{
			window["yaCounter"+account].hit(url, title, referer);
		}
	} catch (e) {;}
};
s.counters._hitTns = function(account, url, title, referer){
	// @see http://www.tns-metrix.ru/help/
	// tnsCounterXXXXXX.hit(tmsec, url, referrer);
	try {
		if (window["tnsCounter"+account.account])
		{
			window["tnsCounter"+account.account].hit(account.tmsec, url, referer);
		}
	} catch (e) {;}
};
s.counters.init = function(){
	s.dom.$window
		.on("counters.hit", function(e, link, title, referer){
			referer = referer || s.referer;
//			console.log(link, title, referer);
			
//			s.counters.hit(link, title, s.referer);
			
			
		});
};