<?php defined('SYSPATH') or die('No direct script access.');

class Acl_Assert_Edit implements Acl_Assert_Interface {

	private $_site_id;

	public function __construct($arguments)
	{
		$this->_site_id = $arguments['site_id'];
	}

	public function assert(Acl $acl, $role = null, $resource = null, $privilege = null)
	{
		return $resource->site_id == $this->_site_id;
	}
}