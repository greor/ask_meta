$(function(){
	store.remove("forms-constructor");
	
	var $holder = $(".js-row-holder");
	var $contaner = $holder.parent();
	var $form = $contaner.closest("form");
	var counter = 0;
	var tplRow = $contaner.find("#tpl-row").html();
	
	function get_field_data(id) {
		var data = store.get('forms-constructor') || [];
		var result = null;
		for (var i = 0; i < data.length; i++) {
			if (data[i].id === id) {
				result = data[i];
				break;
			}
		}
		return result;
	};
	
	function set_field_data(id, value) {
		var data = store.get("forms-constructor") || [];
		var key = null;
		for (var i = 0; i < data.length; i++) {
			if (data[i].id === id) {
				key = i;
				break;
			}
		}
		if (key !== null) {
			data[key] = $.extend(data[key], value);
		} else {
			data.push(value);
		}
		store.set("forms-constructor", data);
	}
	
	function remove_field_data(id) {
		var data = store.get("forms-constructor") || [];
		var key = null;
		for (var i = 0; i < data.length; i++) {
			if (data[i].id === id) {
				key = i;
				break;
			}
		}
		if (key !== null) {
			data.splice(key, 1);
		}
		store.set("forms-constructor", data);
	}
	
	// парсит элементы формы, на выходе объект
	// поддерживает массивы в именах
	function parse_form_fields(list, prefix) {
		var result = {};
		for (var i = 0; i < list.length; i++) {
			var $el = list[i];
			var name = $el.attr("name");
			var chain = name.substring(prefix.length + 1, name.length - 1).split("][");
			var count = chain.length;
			var cursor = result;
			
			while (count > 1) {
				var n = chain.shift();
				cursor[n] = (typeof cursor[n] === "object" && cursor[n] !== null)
					? cursor[n]
					: {};
				cursor = cursor[n];
				count--;
			}
			
			if ($el.is(':checkbox')) {
				cursor[chain.shift()] = $el.is(':checked');
			} else {
				cursor[chain.shift()] = $el.val();
			}
		}
		return result;
	};
	
	function add_row(config) {
		config = config || {};
		
		var maxPos = 0;
		$holder.find('.js-field-position').each(function(){
			var $this = $(this);
			if (parseInt($this.val()) > maxPos) {
				maxPos = parseInt($this.val());
			}
		});
		
		config = $.extend({
			id: ("n" + counter++),
			type: "",
			title: "",
			position: (maxPos + 1),
			required: ""
		}, config);
		
		set_field_data(config.id, config);
		
		var rendered = Mustache.render(tplRow, $.extend(config, {
			settings: (config.type ? render_settings(config.type, config.id) : "")
		}));
		
		$holder.append(rendered);
	};
	
	function render_rows(list) {
		for (var i = 0; i < list.length; i++) {
			var config = list[i];
			add_row(config);
		}
		$holder.find('.js-row-item').each(function(){
			var $this = $(this);
			var type = $this.find('.js-field-type').val();
			if (type) {
				var typeTitle = $this.find(".js-select-type [href=#"+type+"]").text();
				$this.find('.js-field-type-title').text(typeTitle);
			}
			
			if ($this.find('.js-field-required').is(':checked')) {
				$this
					.find(".js-field-required-mark")
					.show();
			}
		});
	};
	
	function render_settings(key, row_id) {
		var settingsTpl = $contaner.find("#tpl-settings-"+key).html();
		var data = get_field_data(row_id);
		return Mustache.render(settingsTpl, data);
	};
	
	render_rows(window.renderRows);
	
	$('.js-delete-all').click(function(){
		$holder.empty();
		store.remove("forms-constructor");
	});
	
	$contaner.find(".js-add-row").click(function(e){
		e.preventDefault();
		add_row();
	});
	
	$contaner.find(".js-add-standard-rows").click(function(e){
		render_rows(window.stdRows);
	});
	
	$holder.on('click', '.js-row-delete', function(e){
		e.preventDefault();
		if (confirm("Подтверждение удаления")) {
			var $row = $(this).closest('.js-row-item');
			$row.remove();
			remove_field_data($row.data('id'));
		}
	});
	
	$holder.on('click', '.js-row-up', function(e){
		e.preventDefault();

		var $this = $(this).closest(".js-row-item");
		var $thisPos = $this.find('.js-field-position');
		var $prev = $this.prev();
		
		if ($prev.length <= 0) {
			return;
		}
		
		var $prevPos = $prev.find('.js-field-position');
		var p = $thisPos.val();
		
		$thisPos.val($prevPos.val());
		$prevPos.val(p);
		
		$this.detach()
			.insertBefore($prev);
	});
	
	$holder.on('click', '.js-row-down', function(e){
		e.preventDefault();
		
		var $this = $(this).closest(".js-row-item");
		var $thisPos = $this.find('.js-field-position');
		var $next = $this.next();
		
		if ($next.length <= 0) {
			return;
		}
		
		var $nextPos = $next.find('.js-field-position');
		var p = $thisPos.val();
		
		$thisPos.val($nextPos.val());
		$nextPos.val(p);
		
		$this.detach()
			.insertAfter($next);
	});
	
	$holder.on("click", ".js-select-type a", function(e){
		e.preventDefault();

		var $this = $(this);
		var $row = $this.closest(".js-row-item");
		var key = $this.attr("href").substr(1);
		
		$this.closest(".btn-group")
			.find(".js-field-type-title").html($this.html()).end()
			.children(".js-field-type").val(key);
		
		var $settingsButton = $row.find(".js-settings-button");
		var id = $row.data('id');
		if ( ! $settingsButton.prop("disabled")) {
			// сохраняем текущие значения
			var list = [];
			$row.find("[name^=set]:not([name*=default])").each(function(){
				var $this = $(this);
				if ($this.attr('name').indexOf("set["+id+"]") === 0) {
					list.push($this);
				}
			});
			set_field_data(id, parse_form_fields(list, "set["+id+"]"));
		}
		
		$row.find(".js-settings-dropdown td")
			.empty()
			.append(render_settings(key, $row.data('id')));
		
		$row.find(".js-settings-button").prop("disabled", false);
	});
	
	$holder.on("click", ".js-settings-button", function(e){
		e.stopPropagation();
		e.preventDefault();
		
		var $this = $(this);
		var $row = $this.closest(".js-row-item");
		var $isActive = $this.hasClass("active");
		
		$holder
			.find(".js-settings-button").removeClass("active").end()
			.find(".js-settings-dropdown").hide();
		
		if ($isActive) {
			$this.removeClass("active");
			$row.find(".js-settings-dropdown")
				.slideUp();
		} else {
			$this.addClass("active");
			$row.find(".js-settings-dropdown")
				.slideDown();
		}
	});
	
	$holder.on("click", ".js-inline-add", function(e){
		var $row = $(this).closest('.controls');
			
		$row.clone()
			.find('input').val('').end()
			.insertAfter($row);
	});
	
	$holder.on("click", ".js-inline-remove", function(e){
		e.preventDefault();
		
		var $this = $(this).closest('.controls');
		if ($this.siblings('.controls').length > 0 && confirm("Подтверждение удаления")) {
			$this.remove();
		}
	});
	
	$holder.on("keyup", ".js-field-label", function(){
		var $this = $(this);
		
		$this.closest(".js-row-item")
			.find(".js-field-title")
			.text($this.val());
	});
	
	$holder.on("click", ".js-field-required", function(){
		var $this = $(this);
		if ($this.is(':checked')) {
			$this.closest(".js-row-item")
				.find(".js-field-required-mark")
				.show();
		} else {
			$this.closest(".js-row-item")
				.find(".js-field-required-mark")
				.hide();
		}
	});
	
});