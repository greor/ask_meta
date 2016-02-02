<?php defined('SYSPATH') or die('No direct script access.');

class Model_Form_Response extends ORM_Base {

	protected $_table_name = 'forms_responses';
	protected $_deleted_column = 'delete_bit';
	protected $_sorting = array(
		'site_id' => 'asc', 
		'id' => 'desc' 
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
			'id' =>	'Record ID',
			'form_id' => 'Form',
			'email' => 'Sended to',
			'text' => 'Message',
			'created' => 'Created',
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
			'form_id' => array(
				array('not_empty'),
				array('digit'),
			),
			'email' => array(
				array('email'),
				array('email_domain'),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'new' => array(
				array(array($this, 'checkbox'))
			),
		);
	}

}
