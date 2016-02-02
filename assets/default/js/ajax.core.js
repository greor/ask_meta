s = window.s || {};
s.dom = s.dom || {};
s.initList = s.initList || [];

s.ajax = {
	useHistory: true,
	referer: null,
	link: null,
	loaded: true,
		
	hashCheck: function(){
		var hash = s.getLocation().hash.replace(/#/g,"");
		if (hash && hash.indexOf("/") == -1) {
			var $target;
			
			// если на странице есть элемент с атрибутом [data-ajax-hash] равным hash,
			// то вызываем событие click на этом элементе
			$target = s.dom.$body.find("[data-ajax-hash="+hash+"]");
			if ($target.length) {
				$target.triggerHandler("click");
				return true;
			} else {
				
				// если находим на странице элемент с id равным hash,
				// то осуществляем прокрутку к данному элементу
				$target = s.dom.$body.find('#'+hash);
				if ($target.length) {
					s.scrollToElement($target);
					return true;
				}
			}
		}
		return false;
	},
	
	load: function(link, $target, options) {
		
		$target = $target || null;
		options = $.extend({
			data: {},
			scrollToTarget: true,
			pushState: true,
			counters: true,
			insertHandler: null
		}, options);
		
		if ( ! this.loaded || ! $target || ! link) {
			return;
		}
		this.loaded = false;
		s.ajaxLoaderShow();
		
		var self = this;
		$.ajax({
			url: link,
			data: $.extend(options.data, {"__ajax__":"1"}),
			type: "GET",
			async: true,
			cache: true,
			dataType: "json"
		}).done(function(data) {
			s.dom.$window
				.triggerHandler("ajax.done");
			
			if (options.insertHandler && $.isFunction(s[options.insertHandler])) {
				
				s[options.insertHandler].apply(s, [$target, data]);
				
			} else {
				
				$target.html(data.content);
				
				if (data.title) {
					document.title = data.title;
				}
				
				if (data.head_tags) {
					s.dom.$head.find(".ajax-head")
						.remove();
					if (data.head_tags.length > 0) {
						var list = data.head_tags;
						var html = "";
						for (var i = 0; i < list.length; i++) {
							var item = list[i];
							
							html += "<"+item.tag;
							for (attrName in item.attr) {
								html += " "+attrName+"=\""+item.attr[attrName]+"\"";
							}
							html += ">";
						}
						s.dom.$head.append(html);
					}
				}
				
				s.removeWindowPageCallbacks();
				s.executeInitList($target);
			}
			
			if (self.useHistory && options.pushState) {
				history.pushState(options, data.title, link);
			}
			
			if (options.counters) {
				s.dom.$window
					.triggerHandler("counters.hit", [link, data.title, self.referer]);
			}
			
			self.referer = self.link;
			self.link = link;
			
			s.dom.$window
				.triggerHandler("ajax.loaded", [self.link, self.referer]);
		}).fail(function(jqXHR, textStatus, errorThrown){
			s.dom.$window
				.triggerHandler("ajax.fail");
			
			if (textStatus !== "abort" && jqXHR.readyState > 0) {
				var message = "Не удалось выполнить запрос";
				var title = "Ошибка";

				if (jqXHR.status == 404) {
					message = "Запрашиваемая страница не найдена";
					title = "404 ошибка";
				}
				if (textStatus === "error") {
					message = "[" + jqXHR.status + "] " + message + "\r\n" + errorThrown;
				}
				
				s.showError(message, title);
			}
		}).always(function(){
			s.dom.$window
				.triggerHandler("ajax.always");
			
			self.loaded = true;
			s.ajaxLoaderHide();
			if ( ! self.hashCheck() && options.scrollToTarget) {
				s.scrollToElement($target);
			}
		});
	},
	
	init: function($holder, options){
		options = $.extend({
			useHistory: true,
			load: {}
		}, options);
		
		this.hashCheck = $.proxy(this.hashCheck, this);
		this.load = $.proxy(this.load, this);
		
		var self = this;
		var loc = s.getLocation();
		this.useHistory = options.useHistory;
		this.link = loc.pathname + loc.search + loc.hash;
		
		if (this.useHistory) {
			s.dom.$window.bind("popstate.main", function(e) {
				var loc = s.getLocation();
				var url = loc.pathname + loc.search;
				var prevUrl = (self.link.indexOf("#") < 0) ? self.link : self.link.split("#")[0];
				
				if (prevUrl === url || ! self.loaded) {
					return false;
				}
				
				var link = url + loc.hash;
				var options = history.state || {};
				options.pushState = false; // блокируем вызов history.pushState в методе load
				
				self.load(link, $holder, options);
			});
			
			s.dom.$window.bind('hashchange.main', function(){
				self.hashCheck();
			});
			this.hashCheck();
		}
		
		s.dom.$body.on('click', 'a', function(e){
			var $this = $(this);
			var link = $this.attr('href');
			
			if ( ! link || link === '#' || $this.data("ajax") === false){
				e.preventDefault();
				return;
			}
			
			if ($this.attr('target') === '_blank') {
				s.dom.$window
					.triggerHandler("counters.hit", [link, 'EXTERNAL FROM - '+document.title, self.referer]);
				return;
			}
			
			if (link.charAt(0) === '/') {
				e.preventDefault();
				
				var $target = $holder;
				
				// в атрибуте [data-ajax-target] можно указать id элемента,
				// в который будет произведена вставка ответа от сервера.
				if ($this.data('ajax-target')) {
					$target = s.dom.$body.find("#" + $this.data('ajax-target'));
				}
				
				// в атрибуте [data-ajax-options] можно указать дополнительные параметры ajax-апроса
				var extraOptions = $this.data('ajax-options') ? $this.data('ajax-options') : {};
				
				self.load(link, $target, $.extend({}, options.load, extraOptions));
			} else if (link.charAt(0) === '#' && link.length > 1) {
				e.preventDefault();
				
				if (self.useHistory) {
					history.pushState(history.state, link + ' - ' + document.title, link);
				}
				s.scrollToElement(link);
			} else {
				s.dom.$window
					.triggerHandler("counters.hit", [link, 'EXTERNAL FROM - '+document.title, self.referer]);
			}
		});
	}
};
