var s = window.s || {};

s.idCntr = 0;
s.idPrefix = "auto_id_";
s.keyCode = {"ESCAPE": 27, "LEFT": 37, "RIGHT": 39};

s.dom = s.dom || {};
s.paths = s.paths || {};
s.initList = s.initList || [];
s.tpl = {
	ajaxLoader: '<div class="ajax-loader" style="display:none;"><img src="{assets}misc/ajax-loader.gif" alt="Загружается..."></div>',
	bgLayout: '<div class="bg-layout" style="display:none;">&nbsp;</div>'
};

/*
 * This selectors will be available through s.dom property
 * (ex. s.dom.$<selector_name>) 
 */
s.cssSelectors = s.cssSelectors || {};
s.assetsPath = "/assets/default/";

s.getId = function(prefix) {
	return (prefix || this.idPrefix) + ++this.idCntr;
};
s.getLocation = function(){
	return history.location || document.location || window.location;
};
s.setupPaths = function(){
	var s = this;
	$.each(this.paths, function(key, val){
		s.paths[key] = s.assetsPath + val;
	});
	
	$.each(this.tpl, function(key, val){
		s.tpl[key] = val.replace('{assets}', s.assetsPath);
	});
};
s.cacheElements = function(){
	this.dom.$window = $(window);
	this.dom.$html = $('html');
	this.dom.$head = $('head');
	this.dom.$body = $('body');
	
	var s = this;
	if (this.cssSelectors) {
		$.each(this.cssSelectors, function(key, val){
			s.dom["$"+key] = $(val);
		});
	}
	$.each(this.tpl, function(key, val){
		s.dom["$"+key] = $(val).appendTo(s.dom.$body);
	});
};
s.scrollToElement = function($target, timeout){
	if (timeout !== undefined) {
		window.setTimeout(function(){
			s.scrollToElement($target);
		}, timeout);
		return;
	}

	try {
		if (typeof $target === 'string') {
			$target = s.dom.$body.find($target);
		}
		
		if ($target.length) {
			window.scrollTo(0, $target.offset().top);
		}
	} catch (e) {;}
};
s.queryParams = function (queryString) {
	queryString = queryString.split("+").join(" ");
    var params = {},
        tokens,
        reg = /[?&]?([^=]+)=([^&]*)/g;
    while (tokens = reg.exec(queryString)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }
    return params;
}
s.subString = function(startString, endString, data){
	var startPos = data.indexOf(startString);
	var endPos = data.indexOf(endString);
	if (startPos > -1 && endPos > startPos) {
		return data.substring(startPos+startString.length, endPos);
	}
	return false;
};
s.executeInitList = function(context){
	for (var i = 0; i < this.initList.length; i++) {
		try {
			if (this[this.initList[i]]) {
				this[this.initList[i]].call(null, context);
			} else if ($.isFunction(this.initList[i])) {
				this.initList[i].call(null, context);
			}
		} catch (e) {;}
	}
	this.initList = [];
};
s.imgPreloader = function(images, callback) {
	var n = images.length;
    var notLoaded = n;
    for (var i = 0; i < n; i++) {
        $(new Image()).load(function(){
            if (--notLoaded < 1 && typeof callback == 'function') {
                callback.apply();
            }
        }).attr('src', images[i]);
    }
};

s.showError = function(msg, title) {
	alert(msg);
};
s.showInfo = function(msg, title) {
	alert(msg);
};

s.ajaxLoaderShow = function(){
	this.dom.$ajaxLoader.css({
		height: Math.max(this.dom.$body.height(), this.dom.$window.height()),
		left: 0,
		top: 0
	}).show();
	
	var $img = this.dom.$ajaxLoader.children('img'),
		$window = this.dom.$window,
		scrollTop = 0;
	
	if ($img.css('position') == 'absolute') {
		scrollTop = $window.scrollTop();
	}
	$img.css({
		top:(($window.height() - $img.height()) / 2 + scrollTop),
		left:(($window.width() - $img.width()) / 2)
	});
};
s.ajaxLoaderHide = function(){
	this.dom.$ajaxLoader
		.hide();
};

s.bgLayoutShow = function(){
	this.dom.$bgLayout.css({
		height: Math.max(this.dom.$body.height(), this.dom.$window.height()),
		left: 0,
		top: 0
	}).fadeIn(200);
};
s.bgLayoutHide = function(){
	this.dom.$bgLayout
		.fadeOut(200);
};
s.proxy = function(){
	this.getId = $.proxy(this.getId, this);
	this.getLocation = $.proxy(this.getLocation, this);
	this.setupPaths = $.proxy(this.setupPaths, this);
	this.cacheElements = $.proxy(this.cacheElements, this);
	this.scrollToElement = $.proxy(this.scrollToElement, this);
	this.queryParams = $.proxy(this.queryParams, this);
	this.executeInitList = $.proxy(this.executeInitList, this);
	this.imgPreloader = $.proxy(this.imgPreloader, this);
	this.ajaxLoaderShow = $.proxy(this.ajaxLoaderShow, this);
	this.ajaxLoaderHide = $.proxy(this.ajaxLoaderHide, this);
	this.bgLayoutShow = $.proxy(this.bgLayoutShow, this);
	this.bgLayoutHide = $.proxy(this.bgLayoutHide, this);
};
s.removeWindowPageCallbacks = function(){
	s.dom.$window
		.off("resize.page")
		.off("scroll.page");
};
