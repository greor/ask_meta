<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Form_Field extends ORM_Helper {

	protected $_safe_delete_field = 'delete_bit';
	protected $_position_fields = array(
		'position' => array(
			'group_by' => array('form_id'),
		),
	);
}
