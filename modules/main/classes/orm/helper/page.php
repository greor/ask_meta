<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Page extends ORM_Helper_Property_Support {

	protected $_property_config = 'pages.properties';
	protected $_safe_delete_field = 'delete_bit';
	protected $_position_type = self::POSITION_COMPLEX;
	protected $_position_fields = array(
		'position' => array(
			'group_by' => array('parent_id'),
			'order_by' => array('name' => 'ASC', 'id' => 'ASC'),
		),
	);

}