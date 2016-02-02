<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Form extends ORM_Helper {

	protected $_safe_delete_field = 'delete_bit';
	protected $_on_delete_cascade = array('fields');
}
