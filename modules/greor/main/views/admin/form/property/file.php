<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$value = empty($value) ? '' : $value;
	
	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;
	
	$controls_class = empty($controls_class) ? '' : $controls_class;
	$control_id = empty($control_id) ? $field.'_field' : $control_id;
	$controll_name = empty($controll_name) ? $field : $controll_name;
	$error_class = isset($errors[$field]) ? ' error' : '';
	
?>
	<div class="control-group <?php echo $error_class; ?>">
		<label class="control-label" for="<?php echo $control_id; ?>">
<?php
			echo __($labels[ $field ]),
				in_array($field, $required) ? '<span class="required">*</span>' : '',
				'&nbsp;:&nbsp;';
?>
		</label>
		<div class="controls <?php echo $controls_class; ?>">
<?php 
			if ( ! empty($value)) {
				
				$mime_group = Ku_File::mime_group(realpath(DOCROOT.$value));
				if ($mime_group == 'image') {
					
					$thumb = Thumb::uri('admin_prop_image_300', $value);
					echo HTML::anchor($value, HTML::image($thumb), array(
						'class' => 'image-holder',
						'title' => '',
						'target' => '_blank',
					));
					
				} else {
					echo HTML::anchor($value, __('Open in new window'), array(
						'class' => 'file-link',
						'target' => '_blank',
					));
				}
			}
	
			$attr = array(
				'id' => $control_id,
			);
			if ( ! empty($accept)) {
				$attr['accept'] = $accept;
			}
			
			echo Form::file($controll_name, $attr);
		
			if ( ! empty($help_text)) {
				echo '<p class="help-block help-text"><small><strong>',
					HTML::chars($help_text),
				'</strong></small></p>';
			}
		
			if (isset($errors[$field])) {
				echo '<p class="text-error">', HTML::chars($errors[$field]), '</p>';
			}
		
			if ( ! empty($value)) {
				$_for = $control_id.'_delete';
				echo '<label class="checkbox" for="'.$_for.'">',
					Form::checkbox($controll_name, '', FALSE, array(
						'id' => $_for,
					)), __('Delete file'), '</label>';
			}
?>
		</div>
	</div>