<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Property_Support extends ORM_Helper {
	
	/**
	 * Config name with property definition
	 * @var array
	 */
	protected $_property_config = NULL;
	
	/**
	 * Helper for work with properties
	 * @var Helper_Property
	 */
	protected $_property_helper = NULL;
	
	public function property_helper()
	{
		if (empty($this->_property_config)) {
			throw new Exception('ORM properties config not exist.');
		}
		
		if ($this->_property_helper === NULL) {
			$this->_property_helper = new Helper_Property($this->_property_config, $this->_orm->object_name());
		}
		
		return $this->_property_helper;
	}
	
	public function property_list()
	{
		$cache_key = $this->property_cache_key();
		$properties = Cache::instance('properties')
			->get($cache_key);
			
		if ($properties === NULL) {
			$helper_propery = $this->property_helper();
			$helper_propery->set_owner_id($this->_orm->id);
			$properties = $helper_propery->get_list();
			Cache::instance('properties')
				->set($cache_key, $properties);
		}
		
		return $properties;
	}
	
	public function save(array $values, Validation $validation = NULL)
	{
		$parent_return = parent::save($values, $validation);
	
		$prop_values = Arr::get($values, 'properties', array());
		
		if (Ku_Upload::valid($prop_values)) {
			$prop_values = Helper_Property::extract_files($prop_values);
		}
		
		$files = Arr::get($_FILES, 'properties', array());
		$prop_values = $prop_values + Helper_Property::extract_files($files);
		unset($files);
		
		if ( ! empty($prop_values)) {
			$orm = $this->_orm;
			
			$user_id = 0;
			if (array_key_exists('creator_id', $orm->table_columns())) {
				$user_id = $orm->creator_id;
			}
			
			$helper_propery = $this->property_helper();
			$helper_propery->set_owner_id($orm->id);
			$helper_propery->set_user_id($user_id);
			
			foreach ($prop_values as $_name => $_value) {
				$helper_propery->set($_name, $_value);
			}
			
			$this->property_cache_clear();
		}
		
		return $parent_return;
	}
	
	public function delete($real_delete, array $where = NULL, $cascade_delete = TRUE, $is_slave_delete = FALSE)
	{
		$orm = $this->_orm;
	
		$helper_propery = $this->property_helper();
		$properties = $helper_propery->get_list();
		if ( ! empty($properties)) {
			
			$user_id = 0;
			if (array_key_exists('deleter_id', $orm->table_columns())) {
				$user_id = $orm->deleter_id;
			}
			
			$helper_propery->set_owner_id($orm->id);
			$helper_propery->set_user_id($user_id);
			
			foreach ($properties as $_name => $_v) {
				$helper_propery->remove($_name);
			}
			
			$this->property_cache_clear();
		}
		
		return parent::delete($real_delete, $where, $cascade_delete, $is_slave_delete);
	}
	
	private function property_cache_key()
	{
		return $this->_property_config.':'.$this->_orm->id;
	}
	
	private function property_cache_clear()
	{
		Cache::instance('properties')
			->delete($this->property_cache_key());
	}
	
}
