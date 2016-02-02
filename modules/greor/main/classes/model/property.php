<?php defined('SYSPATH') or die('No direct script access.');

class Model_Property extends ORM_Base {

	protected $_table_name = 'properties';
	protected $_deleted_column = 'delete_bit';
	
	protected $_has_many = array(
		'enum' => array(
			'model' => 'property_Enum',
			'foreign_key' => 'property_id',
		),
	);

	protected $_has_one = array(
		'text' => array(
			'model' => 'property_Text',
			'foreign_key' => 'property_id',
		),
	);
	
	public function labels()
	{
		return array(
			'owner_id' => 'Owner ID',
			'owner' => 'Owner',
			'name' => 'Name',
			'type' => 'Type',
			'value' => 'Value',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'owner_id' => array(
				array('digit'),
			),
			'owner' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'name' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'type' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'value' => array(
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
		);
	}

}
