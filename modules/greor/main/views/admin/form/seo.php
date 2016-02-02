<?php defined('SYSPATH') or die('No direct access allowed.');

	$output_vars_wrapper = empty($output_vars_wrapper) ? '' : $output_vars_wrapper;
	$hide_toggle = FALSE;
	if (	
		! empty($item->title_tag) OR
		! empty($item->keywords_tag) OR
		! empty($item->description_tag)
	) {
		$hide_toggle = TRUE;
	}
	
	
	$controll_name = 'meta_tags';
	if ( ! empty($output_vars_wrapper)) {
		$controll_name = "{$output_vars_wrapper}[{$controll_name}]";
	}
	$control_id = str_replace(array('[', ']'), '_', $controll_name).'_field';
	echo View_Admin::factory('form/control', array(
		'field' => 'meta_tags',
		'errors' => array(),
		'labels' => array('meta_tags' => __('Additional params')),
		'required' => array(),
		'controls' => Form::hidden($controll_name, '0').Form::checkbox($controll_name, '1', $hide_toggle, array(
			'id' => $control_id,
			'class' => 'toggle-switcher',
			'data-switch-group' => $output_vars_wrapper.'_meta_tags'
		)),
		'control_id' => $control_id,
	));
	
	$controll_name = 'title_tag';
	if ( ! empty($output_vars_wrapper)) {
		$controll_name = "{$output_vars_wrapper}[{$controll_name}]";
	}
	$control_id = str_replace(array('[', ']'), '_', $controll_name).'_field';
	echo View_Admin::factory('form/control', array(
		'field' => 'title_tag',
		'group_class' => 'hide_toggle '.$output_vars_wrapper.'_meta_tags',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input($controll_name, $item->title_tag, array(
			'id' => $control_id,
			'class' => 'input-xxlarge',
		)),
		'control_id' => $control_id,
	));
	
	$controll_name = 'keywords_tag';
	if ( ! empty($output_vars_wrapper)) {
		$controll_name = "{$output_vars_wrapper}[{$controll_name}]";
	}
	$control_id = str_replace(array('[', ']'), '_', $controll_name).'_field';
	echo View_Admin::factory('form/control', array(
		'field' => 'keywords_tag',
		'group_class' => 'hide_toggle '.$output_vars_wrapper.'_meta_tags',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::input($controll_name, $item->keywords_tag, array(
			'id' => $control_id,
			'class' => 'input-xxlarge',
		)),
		'control_id' => $control_id,
	));
	
	$controll_name = 'description_tag';
	if ( ! empty($output_vars_wrapper)) {
		$controll_name = "{$output_vars_wrapper}[{$controll_name}]";
	}
	$control_id = str_replace(array('[', ']'), '_', $controll_name).'_field';
	echo View_Admin::factory('form/control', array(
		'field' => 'description_tag',
		'group_class' => 'hide_toggle '.$output_vars_wrapper.'_meta_tags',
		'errors' => $errors,
		'labels' => $labels,
		'required' => $required,
		'controls' => Form::textarea($controll_name, $item->description_tag, array(
			'id' => $control_id,
			'class' => 'text-area-clear',
		)),
		'control_id' => $control_id,
	));
