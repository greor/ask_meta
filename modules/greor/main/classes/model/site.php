<?php defined('SYSPATH') or die('No direct script access.');

class Model_Site extends ORM_Base {

	protected $_sorting = array('type' => 'ASC', 'name' => 'ASC');
	protected $_deleted_column = 'delete_bit';
	protected $_active_column = 'active';
	protected $_empty_rules = array('check_code');
	
	public function labels()
	{
		return array(
			'type' => 'Type',
			'name' => 'Name',
			'code' => 'Code',
			'logo' => 'Logo',
			'image' => 'Image',
			'sharing_image' => 'Sharing image',
			'mmt' => 'MMT',	//	Moscow Mean Time
			'active' => 'Active',
			'title_tag' => 'Title tag',
			'keywords_tag' => 'Keywords tag',
			'description_tag' => 'Desription tag',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'type' => array(
				array('in_array', array(':value', $this->_table_columns['type']['options'])),
				array(array($this, 'master_not_exist'))
			),
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 255)),
			),
			'code' => array(
				array(array($this, 'check_code')),
				array('max_length', array(':value', 255)),
			),
			'logo' => array(
				array('max_length', array(':value', 255)),
			),
			'image' => array(
				array('max_length', array(':value', 255)),
			),
			'sharing_image' => array(
				array('max_length', array(':value', 255)),
			),
			'mmt' => array(
				array('max_length', array(':value', 6)),
			),
			'title_tag' => array(
				array('max_length', array(':value', 255)),
			),
			'keywords_tag' => array(
				array('max_length', array(':value', 255)),
			),
			'description_tag' => array(
				array('max_length', array(':value', 255)),
			),
		);

	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('UTF8::trim'),
			),
			'code' => array(
				array('strip_tags'),
			),
			'title_tag' => array(
				array('strip_tags'),
			),
			'keywords_tag' => array(
				array('strip_tags'),
			),
			'description_tag' => array(
				array('strip_tags'),
			),
			'active' => array(
				array(array($this, 'checkbox'))
			),
		);
	}

	public function master_not_exist($value)
	{
		if ($value == 'master') {
			return ! ORM::factory('site')
				->select('id')
				->where('type', '=', 'master')
				->and_where('id', '!=', $this->id)
				->find()
				->loaded();
		}

		return TRUE;
	}
	
	public function check_code($value)
	{
		$_site_type = empty($this->_original_values['type'])
			? $this->_object['type']
			: $this->_original_values['type'];
		
		if ($_site_type === 'master') {
			return ! Valid::not_empty($value);
		} else {
			return Valid::not_empty($value);
		}
	}

	public function apply_active_filter()
	{
		if (isset($this->_table_columns[$this->_active_column])) 	{
			$this->where($this->_object_name.'.'.$this->_active_column, '=', 1);
		}
	}

}