<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$value = empty($value) ? '' : $value;
	$field_original = empty($field_original) ? $field : $field_original;
	$image_only = (isset($image_only) AND $image_only === TRUE);
	
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
		if ( ! $image_only) {
			echo Form::file($controll_name, array(
				'id'     => $control_id,
				'accept' => 'image/*'
			));
			if (isset($errors[$field])) {
				echo '<p class="text-error">', HTML::chars($errors[$field]), '</p>';
			}
			
			if ( ! empty($value)) {
				$src = $orm_helper->file_uri($field_original, $value);
				$thumb = Thumb::uri('admin_image_300', $src);
				
				echo HTML::anchor($src, HTML::image($thumb), array(
					'class' => 'image-holder',
					'rel' => 'flyout',
					'title' => '',
					'target' => '_blank',
				));
				
				$_for = $control_id.'_delete';
				echo '<label class="checkbox" for="'.$_for.'">',
					Form::checkbox($controll_name, '', FALSE, array(
						'id' => $_for,
					)), __('Delete image'), '</label>';
			}
		} elseif ( ! empty($value)) {
			$src = $orm_helper->file_uri($field_original, $value);
			$thumb = Thumb::uri('admin_image_300', $src);
			
			echo HTML::anchor($src, HTML::image($thumb), array(
				'class' => 'image-holder',
				'rel' => 'flyout',
				'title' => '',
				'target' => '_blank',
			));
		} else {
			echo '<span id="', $control_id, '" class="plaintext">', __('no image'), '</span>';
		}
		
		if ( ! empty($help_text)) {
			echo '<p class="help-block">', HTML::chars($help_text), '</p>';
		}
?>
		</div>
	</div>