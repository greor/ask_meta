$(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional['ru']);
	jQuery.datepicker.setDefaults({ 
		dateFormat: 'yy-mm-dd' 
	});
});

$(function(){
	$(window).resize(function(){
		$('body > .container-footer').show();
		var $mc = $('body > .container-main'),
		$fc = $('body > .container-footer'),
		mcPaddingTop = parseInt($mc.css("padding-top").replace("px", "")),
		fcHeight = $fc.outerHeight(true);
		$mc.css('min-height', ($(window).height() - mcPaddingTop - fcHeight) + 'px');
	}).resize();
});
$(function(){
	$('.btn-clear').click(function(e){
		$(this).closest('.form-inline')
			.find(':input')
				.not(':button, :submit, :reset, :hidden')
				.val('')
				.removeAttr('checked')
				.removeAttr('selected');
	});
});
$(function(){
	if (location.hash.length > 0) {
		$('.nav-tabs').each(function(){
			$(this).find('[href="'+location.hash+'"]')
				.first()
					.tab('show');
		});
	}
	
	$(".kr-tab-content").each(function(){
		var $this = $(this);
		setTimeout(function(){
			$this.css({
				minHeight: $this.height()+"px"
			})
		}, 0);
	});
});
$(function(){
	$('textarea.text_editor').each(function(){
		CKEDITOR.replace(this, {
			filebrowserUploadUrl: '/uploader?type=Files',
			allowedContent: true,
			width: 534,
			height:$(this).height()
		});
	});

	var $toggleSwitcher = $('.toggle-switcher');
	if ($toggleSwitcher.length) {
		$toggleSwitcher.each(function(){
			var $this = $(this),
				switchGroup = $this.data('switch-group');
			
			if ($this.is(':checked')) {
				$('.hide_toggle.'+switchGroup).each(function(){
					$(this).show();
				});
			} else {
				$('.hide_toggle.'+switchGroup).each(function(){
					$(this).hide();
				});
			}
		});
		
		$('.toggle-switcher').click(function(){
			var switchGroup = $(this).data('switch-group');
			$('.hide_toggle.'+switchGroup+', .hide_toggle_invert.'+switchGroup).each(function(){
				$(this).toggle(1000);
			});
		});
	}
});
$(function(){
	$('.image-holder').lightGallery({
		selector: 'this',
		controls: false,
		mousewheel: false,
		counter: false
	});
});
$(function(){
	$.widget("custom.iconselectmenu", $.ui.selectmenu,{
		_renderItem: function(ul, item){
			var li = $("<li>", { 
				text: item.label
			});
			
			li.addClass("js-image-select-item");
			if (item.disabled) {
				li.addClass("ui-state-disabled");
			}
			
			if (item.index > 0) {
				var icon = window["set_"+this.element.attr("id")][item.index-1];
				if (icon) {
					$("<span>", {
						style: "background-image: url("+icon+")",
						"class": "ui-icon"
					}).appendTo(li);
				}
			}
	 
	        return li.appendTo(ul);
		}
	});
	
	$('.js-image-select')
		.iconselectmenu();
});

$(function(){
	$(".foreign-element").each(function(){
		var $target = $(this).find("td:first-child");
		$target.html( "<i class=\"is-master icon-home\"></i>" + $target.html());
	});
});







