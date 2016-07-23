<?php defined('SYSPATH') or die('No direct script access.');

class Acl_Assert_Module implements Acl_Assert_Interface {


	public function assert(Acl $acl, $role = null, $resource = null, $privilege = null)
	{
		if ( ! Helper_Page::instance()->not_equal( $resource, 'type', 'module' ) )
		{
			return FALSE;
		}

		return TRUE;
	}
}