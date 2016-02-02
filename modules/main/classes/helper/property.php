<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Property {
	
	const ENUM_MARKER = '::enum::';
	const TEXT_MARKER = '::text::';
	const ORM_NAME = 'property';
	
	protected $_owner;
	protected $_owner_id;
	protected $_user_id;
	protected $_orm_helper;
	protected $_types = '_property_types';
	protected $_type_path_cache = array();
	
	protected $_conf;	
	
	public static function label($name) {
		$label = __($name);
		if ($label == $name) {
			$label = '';
			$chain = explode('.', $name);
			foreach ($chain as $_c) {
				$label .= '.'.__($_c);
			}
			$label = ltrim($label, '.');
		}
	
		return $label;
	}
	
	public static function extract_files($files)
	{
		if (empty($files)) {
			return array();
		}
		
		$return = array();
		if (is_array($files['name'])) {
			$files = array_intersect_key($files, array(
				'name' => TRUE, 
				'type' => TRUE, 
				'tmp_name' => TRUE,
				'error' => TRUE,
				'size' => TRUE,
			));
			
			extract($files);
			
			foreach ($name as $_field => $_name) {
				$_error = Arr::get($error, $_field);
				$return[$_field] = array(
					'name' => $_name,
					'type' => Arr::get($type, $_field),
					'tmp_name' => Arr::get($tmp_name, $_field),
					'error' => Arr::get($error, $_field),
					'size' => Arr::get($size, $_field),
				);
			}
		} else {
			$return = $files;
		}
		
		return $return;
	}
	
	public static function is_empty($value)
	{
		$is_empty = NULL;
		switch ($value['type']) {
			case 'simple':
			case 'file':
			case 'enum':
				$is_empty = empty($value['value']);
				break;
			case 'structure':
				$is_empty = TRUE;
				foreach ($value['value'] as $_n => $_v) {
					$is_empty = ($is_empty AND self::is_empty($_v));
					if ( ! $is_empty) {
						break;
					}
				}
				break;
		}
		return $is_empty;
	}
	
	public function __construct($config, $owner, $owner_id = NULL)
	{
		$this->load_config($config);
		
		$this->_owner = $owner;
		$this->_owner_id = $owner_id;
		
		$this->_types = Kohana::$config->load($this->_types);
		$this->_orm_helper = ORM_Helper::factory(self::ORM_NAME);
	}
	
	public function load_config($config)
	{
		$this->_conf = Kohana::$config
			->load($config);
			
		if ($this->_conf === NULL) {
			throw new Exception('Config missing');
		}
			
		if ( ! is_array($this->_conf)) {
			$this->_conf = $this->_conf->as_array();
		}
	}
	
	public function set_user_id($user_id) 
	{
		$this->_user_id = $user_id;
	}
	
	public function set_owner_id($owner_id) 
	{
		$this->_owner_id = $owner_id;
	}
	
	private function _resolve_name($type_name, $chain)
	{
		if (empty($type_name)) {
			throw new Exception('Empty type name');
		}
		
		$type_conf = $this->_types->get($type_name);
		if (empty($type_conf)) {
			throw new Exception('Type not exist');
		}
		
		$return = $type_name;
		switch ($type_conf['type']) {
			case 'simple':
			case 'file':
			case 'enum':
			case 'text':
				if ( ! empty($chain)) {
					throw new Exception('Incorrect format');
				}
				break;
			case 'structure':
				if ( ! empty($chain)) {
					$type_name = Arr::get($type_conf['fields'], array_shift($chain));
					$return = $this->_resolve_name($type_name, $chain);
				}
				break;
		}
		
		return $return;
	}
	
	protected function get_type_name($name) {
		$chain = explode('.', $name);
		$type_name = Arr::get($this->_conf, array_shift($chain));
		
		return $this->_resolve_name($type_name, $chain);
	}
	
	
	/**
	 * @throws Exception
	 */
	public function get_type($name)
	{
		$type_name = $this->get_type_name($name);
		return $this->_types->get($type_name);
	}
	
	protected function save($name, $value, $type)
	{
		if (empty($value)) {
			$this->clear($name);
			return;
		}
		
		$data = array(
			'owner' => $this->_owner,
			'owner_id' => (int) $this->_owner_id,
			'name' => $name,
			'type' => $type['type'],
			'value' => $value,
		);
		
		switch ($type['type']) {
			case 'file':
				$this->_orm_helper
					->is_file(TRUE);
				break;
			case 'enum':
			case 'simple':
				$this->_orm_helper
					->is_file(FALSE);
				break;
		}
		
		$this->_orm_helper->orm()
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $data['owner_id'])
			->and_where('name', '=', $data['name'])
			->find();
		
		$validation_ex = Validation_Ext::factory(array(
			'value' => $value,
		));
		if ( ! empty($type['rules'])) {
			$validation_ex->rules('value', $type['rules']);
		}
		
		if ($this->_orm_helper->orm()->loaded()) {
			$data['updated'] = date('Y-m-d H:i:s');
			$data['updater_id'] = $this->_user_id;
		} else {
			$data['creator_id'] = $this->_user_id;
		}
			
		$this->_orm_helper
			->save($data, $validation_ex);
	}
	
	protected function fetch($name, $type)
	{
		$orm = $this->_orm_helper->orm();
		$orm->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', '=', $name)
			->find();
	
		if ( ! $orm->loaded()) {
			return NULL;
		}
	
		return $this->format_result($type, $orm->as_array());
	}
	
	protected function clear($name) {
		$helper_orm = $this->_orm_helper;
		$result = $helper_orm->orm()
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', '=', $name)
			->find_all();
			
		foreach ($result as $_orm) {
			$type = $this->get_type($_orm->name);
				
			if ($type['type'] == 'file') {
				$this->_orm_helper
					->is_file(TRUE);
			} else {
				$this->_orm_helper
					->is_file(FALSE);
			}
				
			$helper_orm->orm($_orm);
			$helper_orm
				->save(array('value' => ''));
		}
	}
	
	protected function save_structure($name, $value, $type)
	{
		if (empty($value)) {
			$this->clear_structure($name);
		}
		
		if (empty($type['fields'])) {
			return FALSE;
		}
		
		$fields = $type['fields'];
		foreach ($value as $_name => $_value) {
			if ( ! array_key_exists($_name, $fields)) {
				continue;
			}
			
			$_path = $name.'.'.$_name;
			$_type = is_array($fields[$_name]) 
				? $fields[$_name] 
				: $this->get_type($_path);
			
			$this->save($_path, $_value, $_type);
		}
	}
	
	protected function fetch_structure($name, $type)
	{
		$orm = $this->_orm_helper->orm();
		$result = $orm->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', 'LIKE', $name.'%')
			->find_all();
	
		if ($result->count() <= 0) {
			return NULL;
		}
			
		$return = array();
		foreach ($result as $_orm) {
				
			/*
			 * Если загруженного типа свойства нет в описании,
			 * то не выводим его, но оставляем храниться в БД.
			 */
			try {
				$_type = $this->get_type($_orm->name);
			} catch (Exception $e) {
				continue;
			}
			switch ($_type['type']) {
				case 'simple':
				case 'file':
					$_value = $this->format_result($_type, $_orm->as_array());
					break;
				case 'enum':
					$_value = $this->format_result($_type, $_orm->as_array());
					$_value['value'] = $this->_enum_values($_type, $_orm);
					break;
				case 'structure':
					continue 2;
			}
				
			$this->_structure_put_to_array($_orm->name, $return, $_value);
		}
	
		return Arr::path($return, $name, array());
	}
	
	protected function clear_structure($name) 
	{
		$orm = $this->_orm_helper->orm();
		
		$result = $orm
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', 'LIKE', $name.'.%')
			->find_all();
				
		foreach ($result as $_orm) {
			$type = $this->get_type($_orm->name);
	
			switch ($type['type']) {
				case 'simple':
				case 'file':
					$this->clear($_orm->name);
					break;
				case 'enum':
					$this->clear_enum($_orm->name);
					break;
				case 'text':
					$this->clear_text($_orm->name);
					break;
			}
		}
	}
	
	private function _structure_put_to_array($path, & $array, $value)
	{
		$chain = explode('.', $path);
	
		$ptr = & $array;
		$stop = FALSE;
		while ( ! $stop) {
			$_c = array_shift($chain);
				
			if ( ! isset($ptr[ $_c ]['value'])) {
				$ptr[ $_c ]['value'] = array();
			}
			if ( ! empty($chain)) {
				$ptr = & $ptr[ $_c ]['value'];
			} else {
				$ptr = & $ptr[ $_c ];
				$stop = TRUE;
			}
		}
		$ptr = $value;
		unset($ptr);
	}
	
	/**
	 *  Save enum property with set.
	 *  Values that are not in the set will be removed
	 *	$value array contain 'key' values from config, also can contain list of arrays
	 *	each of them contain ('key' => <value>) and other (other can processed in inherited classes
	 *	and saved in table)
	 *
	 * @param string $name
	 * @param array
	 * @param array $type
	 */
	protected function save_enum($name, $values, $type)
	{
		if (empty($values)) {
			$this->clear_enum($name);
			return;
		}
	
		$this->save($name, self::ENUM_MARKER, $type);
		$property_orm = $this->_orm_helper->orm();
		$property_id = (int) $property_orm->id;
	
		$value_id_db = $property_orm->enum
			->find_all()
			->as_array(NULL, 'value_id');
		$data = $this->_enum_make_data($values, $type, $property_id);
	
		if ( ! empty($data)) {
			$value_id = array_keys($data);
				
			$to_insert = array_diff($value_id, $value_id_db);
			$to_update = array_intersect($value_id_db, $value_id);
			$to_delete = array_diff($value_id_db, $value_id);
				
			$this->_enum_insert($data, $to_insert);
			$this->_enum_update($data, $to_update);
			$this->_enum_delete($property_id, $to_delete);
		} elseif ($data === FALSE) {
			$this->_enum_delete($property_id, $value_id_db);
		}
	}
	
	protected function add_enum($name, $values, $type)
	{
		$this->save($name, self::ENUM_MARKER, $type);
		$property_orm = $this->_orm_helper->orm();
		$property_id = (int) $property_orm->id;
	
		$value_id_db = $property_orm->enum
			->find_all()
			->as_array(NULL, 'value_id');
		$data = $this->_enum_make_data($values, $type, $property_id);
	
		if ( ! empty($data)) {
			$value_id = array_keys($data);
				
			$to_insert = array_diff($value_id, $value_id_db);
			$this->_enum_insert($data, $to_insert);
		}
	}
	
	protected function fetch_enum($name, $type)
	{
		$return = $this->fetch($name, $type);
		if ($return !== NULL) {
			$return['value'] = $this->_enum_values($type, $this->_orm_helper->orm());
		}
	
		return $return;
	}
	
	protected function clear_enum($name) 
	{
		$orm = $this->_orm_helper->orm();
		
		$orm
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', '=', $name)
			->find();
		
		$to_delete = $orm->enum
			->find_all()
			->as_array(NULL, 'value_id');			
			
		$this->_enum_delete($orm->id, $to_delete);
	}
	
	private function _enum_insert($data, $to_insert)
	{
		$fields = array_keys(reset($data));
		$table_name = $this->_orm_helper->orm()
			->enum->table_name();
		
		if ( ! empty($to_insert)) {
			$to_insert = array_flip($to_insert);
			$_data = array_intersect_key($data, $to_insert);
			foreach ($_data as $_d) {
				DB::insert($table_name, $fields)
					->values($_d)
					->execute();
			}
		}
	}
	
	private function _enum_update($data, $to_update)
	{
		$table_name = $this->_orm_helper->orm()
			->enum->table_name();
		if ( ! empty($to_update)) {
			$_data = array_intersect_key($data, $to_update);
			foreach ($_data as $_values) {
				$_pairs = array_diff_key($_values, array(
					'property_id' => FALSE,
					'value_id' => FALSE,
				));
					
				if (empty($_pairs)) {
					continue;
				}
					
				DB::update($table_name)
					->set($_pairs)
					->where('property_id', '=', $_values['property_id'])
					->where('value_id', '=', $_values['value_id'])
					->execute();
			}
		}
	}
	
	private function _enum_delete($property_id, $to_delete)
	{
		$table_name = $this->_orm_helper->orm()
			->enum->table_name();
		
		if ( ! empty($to_delete)) {
			DB::delete($table_name)
				->where('property_id', '=', $property_id)
				->where('value_id', 'IN', $to_delete)
				->execute();
		}
	}
	
	private function _enum_make_data($values, $type, $property_id) 
	{
		$return = array();
		if (is_array($values)) {
			$temp = array();
			
			foreach ($type['set'] as $_id => $_row) {
				$temp[$_row['key']] = $_id;
			}
			
			foreach ($values as $_value) {
				$_data = array();
				if (is_array($_value)) {
					$_key = $_value['key'];
					$_data = array_diff_key($_value, array(
						'key' => FALSE,
						'value' => FALSE,
					));
				} else {
					$_key = $_value;
				}
				
				if ( ! array_key_exists($_key, $temp)) {
					continue;
				}
				
				$_value_id = $temp[$_key];
				$_item = array(
					'property_id' => $property_id,
					'value_id' => $_value_id,
				) + $_data;
				
				$return[$_value_id] = $_item;
			}
		} else {
			$return = ($values === FALSE) ? FALSE : array();
		}
		
		return $return;
	}
	
	private function _enum_values($type, $orm) {
		$return = array();
		$enum_values = $type['set'];
		if ( ! empty($enum_values)) {
			$enum = $orm->enum
				->find_all();
	
			foreach ($enum as $_orm) {
				$_value_id = $_orm->value_id;
				if ( ! array_key_exists($_value_id, $enum_values)) {
					continue;
				}
				$return[$_value_id] = $enum_values[$_value_id];
			}
		}
		return $return;
	}
	
	private function _enum_get_id($set, $key)
	{
		if ( ! is_array($key)) {
			$key = array($key);
		}
	
		$tmp = array();
		foreach ($set as $_id => $_val) {
			$tmp[$_val['key']] = $_id;
		}
	
		$return = array();
		foreach ($key as $_k) {
			if ( ! array_key_exists($_k, $tmp)) {
				continue;
			}
			$return[$_k] = $tmp[$_k];
		}
	
		return $return;
	}
	
	protected function save_text($name, $value, $type)
	{
		if (empty($value)) {
			$this->clear_text($name);
			return;
		}
		$this->save($name, self::TEXT_MARKER, $type);
	
		$property_orm = $this->_orm_helper->orm();
		$property_id = (int) $property_orm->id;
	
		$table_name = $property_orm->text
			->table_name();
	
		if ($property_orm->text->loaded()) {
			DB::update($table_name)
				->set(array(
					'value' => $value
				))
				->where('property_id', '=', $property_id)
				->execute();
		} else {
			DB::insert($table_name, array('property_id', 'value'))
				->values(array($property_id, $value))
				->execute();
		}
	}
	
	protected function fetch_text($name, $type)
	{
		$return = $this->fetch($name, $type);
		if ($return !== NULL) {
			$return['value'] = $this->_orm_helper->orm()->text->value;
		}
		
		return $return;
	}
	
	protected function clear_text($name) {
		$orm = $this->_orm_helper->orm();
		$result = $orm
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', '=', $name)
			->find_all();
			
		foreach ($result as $_orm) {
			$_orm_text = $_orm->text;
			if ($_orm_text->loaded()) {
				$_orm_text
					->delete();
			}
		}
	}
	
	protected function format_result($type, $value)
	{
		$return = array(
			'name' => $value['name'],
			'type' => $type['type'],
			'params' => Arr::get($type, 'params'),
		);
	
		switch ($type['type']) {
			case 'enum':
				$this->_orm_helper
					->is_file(FALSE);
				$return['multiple'] = empty($type['multiple']) ? FALSE : $type['multiple'];
				$return['set'] = $type['set'];
				$return['value'] = array();
				break;
			case 'file':
				if ( ! empty($value['value'])) {
					$this->_orm_helper
						->is_file(TRUE);
					$return['value'] = $this->_orm_helper->file_uri('value', $value['value']);
				} else {
					$return['value'] = '';
				}
				break;
			case 'simple':
			case 'text':
				$this->_orm_helper
					->is_file(FALSE);
				$return['value'] = $value['value'];
				break;
			case 'structure':
				$this->_orm_helper
					->is_file(FALSE);
				/*
				 * Do nothing
				 */
				break;
		}
		return $return + $value;
	}
	
	protected function get_empty($path, $type)
	{
		if ( ! is_array($type)) {
			$type = $this->get_type($path);
		}
		
		if (empty($type)) {
			throw new Exception('Type not exist');
		}
		
		$return = NULL;
		switch ($type['type']) {
			case 'simple':
			case 'file':
			case 'text':
				$return = $this->format_result($type, array(
					'name' => $path,
					'value' => '',
				));
				break;
			case 'enum':
				$return = $this->format_result($type, array(
					'name' => $path,
					'value' => array(),
				));
				break;
			case 'structure': 
				$return = $this->format_result($type, array(
					'name' => $path,
					'value' => array(),
				));
				
				foreach ($type['fields'] as $_field => $_type) {
					$return['value'][$_field] = $this->get_empty($path.'.'.$_field, $_type);
				}
				break;
		}
		
		return $return;
	}
	
	public function set($name, $value) 
	{
		$type = $this->get_type($name);
		if (empty($type)) {
			throw new Exception('Type not exist');
		}
		
		switch ($type['type']) {
			case 'structure':
				$this->save_structure($name, $value, $type);
				break;
			case 'enum':
				$value = array_filter($value);
				$this->save_enum($name, $value, $type);
				break;
			case 'text':
				$this->save_text($name, $value, $type);
				break;
			case 'file':
				if (is_array($value) AND $value['error'] !== UPLOAD_ERR_OK) {
					continue;
				}
				$this->save($name, $value, $type);
				break;
			case 'simple':
				$this->save($name, $value, $type);
				break;
		}
	}
	
	public function get($name)
	{
		$type = $this->get_type($name);
		if (empty($type)) {
			throw new Exception('Type not exist');
		}
		
		$return = $this->get_empty($name, $type);
		switch ($type['type']) {
			case 'structure':
				$fetched = $this->fetch_structure($name, $type);
				if ($fetched !== NULL) {
					$return = array_merge_recursive_distinct($return, $fetched);
				}
				break;
			case 'enum':
				$fetched = $this->fetch_enum($name, $type);
				if ($fetched !== NULL) {
					$return = $fetched;
				}
				break;
			case 'text':
				$fetched = $this->fetch_text($name, $type);
				if ($fetched !== NULL) {
					$return = $fetched;
				}
				break;
			case 'simple':
			case 'file':
				$fetched = $this->fetch($name, $type);
				if ($fetched !== NULL) {
					$return = $fetched;
				}
				break;
		}
		return $return;
	}
	
	public function add($name, $value) {
		$type = $this->get_type($name);
		if (empty($type)) {
			throw new Exception('Type not exist');
		}
		
		switch ($type['type']) {
			case 'structure':
				$this->save_structure($name, $value, $type);
				break;
			case 'enum':
				$this->add_enum($name, $value, $type);
				break;
			case 'text':
				$this->save_text($name, $value, $type);
			case 'simple':
			case 'file':
				$this->save($name, $value, $type);
				break;
		}
	}
	
	public function remove($name) {
		$helper_orm = $this->_orm_helper;
		$result = $helper_orm->orm()
			->clear()
			->where('owner', '=', $this->_owner)
			->and_where('owner_id', '=', $this->_owner_id)
			->and_where('name', 'LIKE', $name.'%')
			->find_all();
			
		foreach ($result as $_orm) {
			$type = $this->get_type($_orm->name);
			
			if ($type['type'] == 'file') {
				$this->_orm_helper
					->is_file(TRUE);
			} else {
				$this->_orm_helper
					->is_file(FALSE);
			}
			
			$helper_orm->orm($_orm);
			$helper_orm
				->save(array('deleter_id' => $this->_user_id, 'deleted' => date('Y-m-d H:i:s')))
				->delete(FALSE);
		}
	}
	
	public function get_list()
	{
		$return = array();
		foreach ($this->_conf as $_name => $_) {
			$_val = $this->get($_name);
			$return[$_name] = $_val;
		}
		
		return $return;
	}
	
	/**
	 * Search owner_id by condition (only for 'simple', 'file' and 'enum' types).
	 * Don't search by 'text' properties
	 *
	 * Condition support format:
	 * 		'field_name' => 'value_1'
	 * 				 - equal 'field_name' = 'value_1'
	 * 		'field_name' => array('operator', 'value_1')
	 * 				 - equal 'field_name' 'operator' 'value_1'
	 * 		'field_name' => array('operator', array('value_1'))
	 * 				 - equal 'field_name' 'operator' ('value_1')
	 *
	 * Example:
	 * 		$where = array(
	 *			'field_1' => 'value_1',
	 *			'OR',
	 *			'field_2' => array('IN', array('value_2', 'value_3')),
	 *		);
	 *
	 * Generated in:
	 *		'field_1' = 'value_1'
	 *		OR
	 *		'field_2' IN ('value_2', 'value_3')
	 *
	 *
	 * @param string $owner
	 * @param array $where
	 * @param bool $bulder will return as builder
	 * @return array
	 */
	public function search($where = array(), $bulder = FALSE)
	{
		$table_name = $this->_orm_helper->orm()
			->table_name();
		$table_enum_name =$this->_orm_helper->orm()
			->enum
			->table_name();
	
		$builder = DB::select('owner_id')
			->from($table_name)
			->where('owner', '=', $this->_owner)
			->where('delete_bit', '=', 0);
			
		if ( ! empty($where)) {
			$builder->where_open();
				
			$method = NULL;
			foreach ($where as $_name => $_condition) {
				if (is_int($_name)) { // logic operator
					$_condition = UTF8::strtolower($_condition);
					switch ($_condition) {
						case 'or':
							$method = 'or_where';
							break;
						case 'and':
							$method = 'and_where';
							break;
					}
				} else { // condition
					if ($method === NULL) {
						$method = 'and_where';
					}
					
					$_op = $_val = '';
					if (is_array($_condition)) {
						$_op = $_condition[0];
						$_val = $_condition[1];
					} else {
						$_op = '=';
						$_val = $_condition;
					}
						
					$_type = $this->get_type($_name);
						
					switch ($_type['type']) {
						case 'simple':
						case 'file':
							$builder
								->{$method.'_open'}()
									->where('name', '=', $_name)
									->where('value', $_op, $_val)
								->{$method.'_close'}();
							
							break;
						case 'enum':
							$_val = $this->_enum_get_id($_type['set'], $_val);
							if ( ! empty($_val)) {
								$_sub = DB::select('property_id')
									->from($table_enum_name)
									->where('value_id', 'IN', $_val);
								
								$builder
									->{$method.'_open'}()
										->where('name', '=', $_name)
										->where('id', 'IN', $_sub)
									->{$method.'_close'}();
							}
							break;
					}
					$method = NULL;
				}
			}
				
			$builder->where_close();
		}
			
		if ($bulder) {
			return $builder;
		} else {
			return $builder->execute()
				->as_array(NULL, 'id');
		}
	}
}