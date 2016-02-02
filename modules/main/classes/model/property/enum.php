<?php defined('SYSPATH') or die('No direct script access.');

class Model_Property_Enum extends ORM {

	protected $_table_name = 'properties_enum';
	
	protected $_belongs_to = array(
		'property' => array(
			'model' => 'property',
			'foreign_key' => 'property_id',
		),
	);
	
	public function labels()
	{
		return array(
			'property_id' => 'Property',
			'value_id' => 'Value',
		);
	}

	public function rules()
	{
		return array(
			'property_id' => array(
				array('digit'),
			),
			'value_id' => array(
				array('digit'),
			),
		);
	}

}
