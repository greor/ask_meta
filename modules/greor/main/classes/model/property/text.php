<?php defined('SYSPATH') or die('No direct script access.');

class Model_Property_Text extends ORM {

	protected $_primary_key = 'property_id';
	protected $_table_name = 'properties_text';
	
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
			'value' => 'Text',
		);
	}

	public function rules()
	{
		return array(
			'property_id' => array(
				array('digit'),
			),
		);
	}

}
