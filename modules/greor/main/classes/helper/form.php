<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Form {
	
	public function get_fields($orm)
	{
		$result = array();
		if ( ! $orm->loaded()) {
			return $result;
		}
		
		$fields = $orm->fields
			->find_all();
	
		$result = array();
		foreach ($fields as $_orm) {
			$_values = $_orm->as_array();
			$_additional = unserialize($_values['additional']);
				
			$_item = array_intersect_key($_values, array(
				'id' => TRUE,
				'type' => TRUE,
				'position' => TRUE,
				'title' => TRUE,
				'default' => TRUE,
				'required' => TRUE,
			));
			$_item['required'] = (bool) $_item['required'];
			$_item['init'] = TRUE;
				
			switch ($_values['type']) {
				case 'text':
					if ( ! empty($_additional['email'])) {
						$_item['email'] = TRUE;
					}
					break;
				case 'textarea':
					// Do nothing
					break;
				case 'checkbox':
					// Do nothing
					break;
				case 'select':
					if ( ! empty($_additional['options'])) {
						$_item['options'] = $_additional['options'];
					}
					break;
				case 'date':
					if ( ! empty($_additional['current_time'])) {
						$_item['current_time'] = TRUE;
					}
					break;
				case 'counter':
					if ( ! empty($_additional['range'])) {
						$_item['range'] = $_additional['range'];
					}
					break;
			}
				
			$result[] = $_item;
		}
		
		return $result;
	}
	
}