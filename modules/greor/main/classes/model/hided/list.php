<?php defined('SYSPATH') or die('No direct script access.');

class Model_Hided_List extends ORM_Base {

	protected $_table_name = 'hided_list';
	protected $_sorting = array('object_name' => 'ASC', 'site_id' => 'ASC', 'element_id' => 'ASC');

	public function labels()
	{
		return array(
			'site_id' => 'Site',
			'element_id' => 'Element ID',
			'object_name' => 'Object name',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'site_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'element_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'object_name' => array(
				array('not_empty'),
				array('max_length', array(':value', 50)),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'object_name' => array(
				array('strip_tags'),
			),
		);
	}

}