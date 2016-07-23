$(function(){
	var $modal = $('#nomenclatureModal');
	var $hidden = $("#"+$modal.data("hidden"));
	var $input = $("#"+$modal.data("input"));
	
	$('.js-action-edit').on('click', function(e){
		e.preventDefault();
		
		$.ajax({
			method: "GET",
			dataType: "json",
			cache: true,
			url: $(this).data('source')
		}).done(function(data){
			$modal
				.find('.product-list table')
				.dynatable({
					features: {
						pushState: false,
						perPageSelect: false,
						recordCount: false
					},
					dataset: {
						records: data
					},
					writers: {
						_rowWriter: function(rowIndex, record, columns, cellWriter){
							return '<tr><td data-id="'+record.id+'" data-source="'+record.source+'"><span class="list-title">'+record.name+'</span><span class="caret-holder"></span><ul class="nomenclature-list"></ul></td></tr>';
						}
					},
					inputs: {
						paginationNext: ">",
						paginationPrev: "<",
						labelSearch: "Поиск: ",
						labelPages: "",
					}
				});
			
			$modal.modal('show');
		});
	});
	
	$modal.find('.nomenclature-list').on('click', 'td', function(){
		var $this = $(this);
		$this.parent().addClass('active')
			.siblings('tr').removeClass('active');
	});
	
	$modal.find('.product-list > .table').on('click', 'td', function(){
		var $this = $(this);
		
		if ($this.parent().hasClass('active')) {
			return;
		}
		
		$this.parent().addClass('active')
			.siblings('tr.active')
				.find('.nomenclature-list')
					.slideUp()
					.find('.active')
						.removeClass('active')
					.end()
				.end()
				.removeClass('active');
		
		if ( ! $this.hasClass('loaded')) {
			$.ajax({
				method: "GET",
				dataType: "json",
				cache: true,
				url: $this.data('source')
			}).done(function(data){
				var list = $.map(data, function(record){
					return '<li data-id="'+record.id+'" data-title="'+record.title+'">'+record.name+'</li>';
				});
				
				$this.find('.nomenclature-list')
					.html(list.join(''))
					.slideDown();
			}).always(function(){
				$this.addClass('loaded');
			});
		} else {
			$this.find('.nomenclature-list')
				.slideDown();
		}
		
	});
	
	$modal.find('.product-list').on('click', '.nomenclature-list li', function(e){
		e.preventDefault();
		e.stopPropagation();
		
		var $this = $(this);
		if ($this.hasClass('active')) {
			return;
		}
		$this.addClass('active')
			.siblings()
				.removeClass('active');
	});
	
	$modal.find('.apply-changes').on('click', function(){
		var $element = $modal.find('.product-list .nomenclature-list li.active');
		if ($element.length) {
			$hidden.val($element.data('id'));
			$input.val($element.data('title'));
		} else {
			$hidden.val('');
			$input.val('');
		}
		$modal.modal('hide');
	});
	
});