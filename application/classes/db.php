<?php defined('SYSPATH') OR die('No direct script access.');

class DB extends Kohana_DB {
	
	public static function get_hidden_elements($object_name, $site_id, $as_object = TRUE)
	{
		$table = ORM::factory('hided_List')
			->table_name();
		
		$builder = DB::select('element_id')
			->from($table)
			->where('object_name', '=', strtolower($object_name))
			->where('site_id', '=', $site_id);
			
		if ($as_object) {
			$result = $builder; 
		} else {
			$result = $builder
				->execute()
				->as_array();
		}
		
		return $result;
	}
	
}
