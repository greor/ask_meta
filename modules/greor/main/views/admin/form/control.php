<?php defined('SYSPATH') or die('No direct access allowed.');

	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;

	$tooltip = empty($tooltip) ? '' : $tooltip;
	$group_class = empty($group_class) ? '' : $group_class;
	$controls_class = empty($controls_class) ? '' : $controls_class;
	$control_id = empty($control_id) ? $field.'_field' : $control_id;
	$error_class = isset($errors[$field]) ? ' error' : '';
?>
	<div class="control-group <?php echo $group_class, $error_class; ?>">
		<label class="control-label" for="<?php echo $control_id; ?>">
<?php
			echo __($labels[ $field ]),
				in_array($field, $required) ? '<span class="required">*</span>' : '',
				'&nbsp;:&nbsp;';
?>
		</label>
		<div class="controls <?php echo $controls_class; ?>">
<?php 
			echo $controls; 
			if ( ! empty($tooltip)) {
				echo '<p class="help-block tooltip-container">', HTML::chars($tooltip), '</p>';
			}
			if (isset($errors[$field])) {
				echo '<p class="text-error">', HTML::chars($errors[$field]), '</p>';
			}
?>
		</div>
	</div>
