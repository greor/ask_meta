<?php defined('SYSPATH') or die('No direct access allowed.'); 

	$field = "properties[{$item['name']}]";
	$control_id = md5($field);
	$labels = array(
		$field => __('Value')
	);
	
	switch ($item['type']) {
		case 'structure':
			echo View_Admin::factory('form/property/structure', array(
				'item' => $item
			));
			break;
			
		case 'enum':
			echo View_Admin::factory('form/property/enum', array(
				'item' => $item,
				'field' => $field,
				'control_id' => $control_id,
				'labels' => $labels,
			));
			break;
			
		case 'simple':
			echo View_Admin::factory('form/control', array(
				'field' => $field,
				'labels' => $labels,
				'control_id' => $control_id,
				'controls' => Form::input($field, $item['value'], array(
					'id' => $control_id,
					'class' => 'input-xxlarge',
					'placeholder' => __($title)
				)),
			));
			break;
			
		case 'text':
			echo View_Admin::factory('form/control', array(
				'field' => $field,
				'labels' => $labels,
				'control_id' => $control_id,
				'controls' => Form::textarea($field, $item['value'], array(
					'id' => $control_id,
					'class' => 'text-area-clear',
					'placeholder' => __($title)
				)),
			));
			break;
			
		case 'file':
			echo View_Admin::factory('form/property/file', array(
				'field' => $field,
				'labels' => $labels,
				'control_id' => $control_id,
				'value' => $item['value'],
			));
			break;
		
	}

