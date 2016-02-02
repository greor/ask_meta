<?php defined('SYSPATH') or die('No direct access allowed.');

	$required = empty($required) ? array() : $required;
	$errors = empty($errors) ? array() : $errors;

	$tooltip = empty($tooltip) ? '' : $tooltip;
	$group_class = empty($group_class) ? '' : $group_class;
	$controls_class = empty($controls_class) ? '' : $controls_class;
	$control_id = empty($control_id) ? $field.'_field' : $control_id;
	
	$_control = Form::hidden($field, '').Form::checkbox($field, '1', (bool) $orm_helper->orm()->{$field}, array(
		'id' => $control_id,
	));
	
	echo View_Admin::factory('form/control', array(
		'field' => $field,
		'labels' => $labels,
		'errors' => $errors,
		'required' => $required,
		'tooltip' => $tooltip,
		'group_class' => $group_class,
		'controls_class' => $controls_class,
		'control_id' => $control_id,
		'controls' => $_control,
	));