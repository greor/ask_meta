<?php defined('SYSPATH') or die('No direct script access.');

class Model_Form_Field extends ORM_Base {

	protected $_table_name = 'forms_fields';
	protected $_deleted_column = 'delete_bit';
	protected $_sorting = array(
		'form_id' => 'asc',
		'position' => 'asc'
	);

	protected $_belongs_to = array(
		'form' => array(
			'model' => 'form',
			'foreign_key' => 'form_id',
		),
	);

	public function labels()
	{
		return array(
			'title' => 'Title',
			'default' => 'Default value',
			'required' => 'Required field',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'form_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'type' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'default' => array(
				array('max_length', array(':value', 255)),
			),
			'position' => array(
				array('digit'),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'type' => array(
				array('strip_tags'),
			),
			'label' => array(
				array('strip_tags'),
			),
			'default' => array(
				array('strip_tags'),
			),
			'required' => array(
				array(array($this, 'checkbox'))
			),
		);
	}

}
