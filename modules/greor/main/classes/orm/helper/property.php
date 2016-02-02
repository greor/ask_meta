<?php defined('SYSPATH') or die('No direct script access.');

class ORM_Helper_Property extends ORM_Helper {

	private $_is_file = FALSE;
	protected $_safe_delete_field = 'delete_bit';
	protected $_on_delete_cascade = array('enum');
	
	protected $_is_file_file_fields = array(
		'value' => array(
			'path' => "upload/properies",
			'uri' => NULL,
			'on_delete' => ORM_File::ON_DELETE_RENAME,
			'on_update' => ORM_File::ON_UPDATE_RENAME,
			'allowed_src_dirs' => array(''),
		),
	);
	
	public function file_rules()
	{
		return array(
			'value' => array(
				array('Ku_File::valid'),
				array('Ku_File::size', array(':value', '3M')),
			),
		);
	}

	public function is_file($mode)
	{
		$this->_is_file = (bool) $mode;
	}
	
	private function _set_file_fields() {
		if ($this->_is_file) {
			$this->_file_fields = $this->_is_file_file_fields;
		} else {
			$this->_file_fields = array();
		}
		$this->_initialize_file_fields();
	}
	
	public function save(array $values, Validation $validation = NULL)
	{
		$this->_set_file_fields();
		return parent::save($values, $validation);
	}
	
	protected function _check_file_field($field)
	{
		$this->_set_file_fields();
		
		parent::_check_file_field($field);
	}
	
}