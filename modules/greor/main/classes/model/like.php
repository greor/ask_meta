<?php defined('SYSPATH') or die('No direct script access.');

class Model_Like extends Kohana_ORM {

	protected $_table_name = 'likes';
	public $expires_table = 'likes_expires';

	public function labels()
	{
		return array(
			'id' => 'Element ID',
			'model' => 'Model',
			'count' => 'Count',
		);
	}

	public function rules()
	{
		return array(
			'id' => array(
				array('digit'),
			),
			'model' => array(
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
		);
	}
}
