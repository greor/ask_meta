<?php defined('SYSPATH') or die('No direct access allowed.');

	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;
	$control_id = empty($control_id) ? $field.'_field' : $control_id;
	$set_default = ( ! isset($set_default)) ? TRUE : $set_default;
	$readonly = empty($readonly) ? FALSE : TRUE;
	$uniqid = uniqid();

	if ( ! empty($output_vars_wrapper)) {
		$controll_name_1 = "{$output_vars_wrapper}[{$field}][date]";
	} else {
		$controll_name_1 = "{$field}[date]";
	}
	if ( ! empty($output_vars_wrapper)) {
		$controll_name_2 = "{$output_vars_wrapper}[{$field}][time]";
	} else {
		$controll_name_2 = "{$field}[time]";
	}
	
	
	if ($orm->loaded() AND ! empty($orm->{$field}) AND $orm->{$field} !== '0000-00-00 00:00:00') {
		$date_ts = strtotime($orm->{$field});
		$_date = date('Y-m-d', $date_ts);
		$_time = date('H:i', $date_ts);
	} elseif ($set_default) {
		$_date = date('Y-m-d');
		$_time = date('H:i');
	} else {
		$_date = '';
		$_time = '';
	}
	
	$attr_ext = array();
	if ($readonly) {
		$attr_ext['readonly'] = 'readonly';
	}
	
	echo View_Admin::factory('form/control', array(
		'field'    => $field,
		'errors'   => $errors,
		'labels'   => $labels,
		'required' => $required,
		'controls' => Form::input($controll_name_1, $_date, array(
			'id'    => $control_id,
			'class' => 'input-small date_'.$uniqid,
		) + $attr_ext).Form::input($controll_name_2, $_time, array(
			'class' => 'input-mini time_'.$uniqid,
		) + $attr_ext),
	));
	unset($controll_name_1);
	unset($controll_name_2);
	
	if ( ! $readonly):
?>
	<script>
	$(document).ready(function(){
		$('.time_<?php echo $uniqid; ?>').timepicker({});
		$('.date_<?php echo $uniqid; ?>').datepicker({
			 changeYear: true,
			showButtonPanel: true
		});
	});
	</script>	
<?php 
	endif;
	