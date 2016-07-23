<?php defined('SYSPATH') or die('No direct script access.');

class Acl_Assert_Hide implements Acl_Assert_Interface {

	protected $_site_id;
	protected $_site_id_master;

	public function __construct($arguments)
	{
		$this->_site_id = $arguments['site_id'];
		$this->_site_id_master = $arguments['site_id_master'];
	}

	public function assert(Acl $acl, $role = null, $resource = null, $privilege = null)
	{
		if ( $resource->site_id != $this->_site_id_master OR $this->_site_id == $this->_site_id_master )
		{
			return FALSE;
		}

		return TRUE;
	}
}