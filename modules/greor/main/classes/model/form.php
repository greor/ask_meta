<?php defined('SYSPATH') or die('No direct script access.');

class Model_Form extends ORM_Base {

	protected $_table_name = 'forms';
	protected $_deleted_column = 'delete_bit';
	protected $_sorting = array(
		'site_id' => 'asc', 
		'public_date' => 'desc', 
		'close_date' => 'desc'
	);

	protected $_has_many = array(
		'fields' => array(
			'model' => 'form_Field',
			'foreign_key' => 'form_id',
		),
		'responses' => array(
			'model' => 'form_Response',
			'foreign_key' => 'form_id',
		),
	);

	public function labels()
	{
		return array(
			'owner' => 'Owner',
			'active' => 'Active',
			'captcha' => 'Captcha',
			'title' => 'Title',
			'email' => 'Send to (e-mail)',
			'text' => 'Text',
			'text_show_top' => 'Display text on top',
			'public_date' => 'Public date',
			'close_date' => 'Close date',
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
			'title' => array(
				array('not_empty'),
				array('max_length', array(':value', 255)),
			),
			'email' => array(
				array('email'),
				array('email_domain'),
			),
			'public_date' => array(
				array('not_empty'),
				array('date'),
			),
			'close_date' => array(
				array('date'),
			),
		);
	}

	public function filters()
	{
		return array(
			TRUE => array(
				array('trim'),
			),
			'title' => array(
				array('strip_tags'),
			),
			'msg_label' => array(
				array('strip_tags'),
			),
			'active' => array(
				array(array($this, 'checkbox'))
			),
			'captcha' => array(
				array(array($this, 'checkbox'))
			),
			'text_top' => array(
				array(array($this, 'checkbox'))
			),
		);
	}

	public function apply_mode_filter()
	{
		parent::apply_mode_filter();

		if($this->_filter_mode == ORM_Base::FILTER_FRONTEND) {
			$date = date('Y-m-d H:i:00');
			$this
				->where($this->_object_name.'.public_date', '<=', $date)
				->and_where_open()
					->where($this->_object_name.'.close_date', '>', $date)
					->or_where($this->_object_name.'.close_date', '=', '0000-00-00 00:00:00')
				->and_where_close();
		}
	}

}
