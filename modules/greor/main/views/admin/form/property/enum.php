<?php defined('SYSPATH') or die('No direct access allowed.'); 

	if ($item['multiple'] == TRUE) {
	
		foreach ($item['set'] as $_conf) {
			$_key = $_conf['key'];
				
			$_field = "{$field}[{$_key}]";
			$_control_id = md5($_field);
				
			$_checked = FALSE;
			foreach ($item['value'] as $_val) {
				if ($_key == $_val['key']) {
					$_checked = TRUE;
					break;
				}
			}
			
			echo View_Admin::factory('form/control', array(
				'field' => $_field,
				'labels' => array(
					$_field => $_conf['value']
				),
				'control_id' => $_control_id,
				'controls' => Form::hidden($_field, '').Form::checkbox($_field, $_key, $_checked, array(
					'id' => $_control_id,
				)),
			));
		}
	
	} else {
		$_selected = reset($item['value']);
	
		$_field = "{$field}[]";
		$_control_id = md5($_field);
	
		foreach ($item['set'] as $_conf) {
			$options[$_conf['key']] = __($_conf['value']);
		}
	
		$script = $class = '';
		if ($icons = Arr::path($item, 'params.icons')) {
			$class = ' js-image-select';
			$script = '<script>var set_'.$_control_id.'='.json_encode($icons).'</script>';
		}
	
		echo View_Admin::factory('form/control', array(
			'field' => $_field,
			'labels' => array(
				$_field => __('Value')
			),
			'control_id' => $_control_id,
			'controls' => Form::select($_field, array('' => '') + $options, $_selected['key'], array(
				'id' => $_control_id,
				'class' => 'input-xxlarge'.$class,
			)).$script,
		));
	}