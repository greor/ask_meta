<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;

	
	$control_id = empty($control_id) ? $type_field.'_field' : $control_id;
	$error_class = (isset($errors[ $type_field ]) OR isset($errors[ $data_field ])) ? ' error' : '';
	
?>
	<div class="control-group <?php echo $error_class; ?>">
		<label class="control-label" for="<?php echo $control_id; ?>">
<?php
			echo __($labels[ $type_field ]),
				in_array($type_field, $required) ? '<span class="required">*</span>' : '',
				'&nbsp;:&nbsp;';
?>
		</label>
		<div class="controls">
<?php
			$_page_types = Kohana::$config->load('_pages.type');
			if ( ! $ACL->is_allowed($USER, $page, 'link_module')) {
				unset($_page_types['module']);
			}

			echo Form::select('type', $_page_types, $page->type, array(
				'id' => 'type_field',
				'class' => 'input-xxlarge',
			)), Form::hidden('data', $page->data);

			
			$_empty_row = array('-' => '');
			echo Form::select('_modules', $_empty_row + $modules, '-', array(
				'id' => 'type_module',
				'class' => 'input-xxlarge page-type-select js-hidden',
			));
			echo Form::select('_pages', $_empty_row + $pages_list, '-', array(
				'id' => 'type_page',
				'class' => 'input-xxlarge page-type-select js-hidden',
			));
			echo Form::input('_redirect_url', '', array(
				'id' => 'type_url',
				'class' => 'input-xxlarge page-type-select js-hidden',
			));
			
			
			if (isset($errors[ $type_field ])) {
				echo '<p class="text-error">', HTML::chars($errors[ $type_field ]), '</p>'; 
			} 
			if (isset($errors[ $data_field ])) {
				echo '<p class="text-error">', HTML::chars($errors[ $data_field ]), '</p>'; 
			}
?>
		</div>
		<script>
			$(function(){
	
				var $typeField = $('#type_field'),
					$container = $typeField.closest('.controls'),
					$dataField = $('input[name="data"]', $container);
	
				function hide_controls() {
					$('.js-hidden', $container).each(function(){
						var $this = $(this);
						if ($this.is('input')) {
							$this.val('');
						} else if ($this.is('select')) {
							$this.find("option:first")
								.attr("selected", "selected");
						}
						$this.hide();
					});
				}
	
				$('.js-hidden', $container).change(function(){
					var $this = $(this),
						$val = '';
	
					if ($this.is('input')) {
						$val = $this.val();
					} else if ($this.is('select')) {
						$val = $('option:selected', $this).val();
					}
					$dataField.val($val);
				});
	
				$typeField.change(function(){
					hide_controls();
					$dataField.val('-');
	
					var type = $('option:selected', $typeField).val();
					$('#type_'+type).show();
	
					if ($('option:selected', $typeField).val() == 'url') {
						$('input[name="uri"]')
							.closest('.control-group')
							.hide();
					} else {
						$('input[name="uri"]')
							.closest('.control-group')
							.show();
					}
				});
	
	
				hide_controls();
	
				var curType = $('option:selected', $container).val();
				var $curElement = $('#type_'+curType, $container);
	
				if ($curElement.length) {
					var $val = '';
					if ($curElement.is('input')) {
						$curElement.val( $dataField.val() );
					} else if ($curElement.is('select')) {
						$curElement.find('option[value="' + $dataField.val() + '"]')
							.attr('selected', 'selected');
					}
	
					$curElement.show();
	
					if ($('option:selected', $typeField).val() == 'url') {
						$('input[name="uri"]')
							.closest('.control-group')
								.hide();
					} else {
						$('input[name="uri"]')
							.closest('.control-group')
								.show();
					}
				}
			});
		</script>
	</div>