<?php defined('SYSPATH') or die('No direct script access.');

class Injector_Base {
	
	protected $request;
	protected $user;
	protected $acl;
	protected $params;
	
	public function __construct(Request $request, ORM $user, A2 $acl, $params = NULL)
	{
		$this->request = $request;
		$this->user = $user;
		$this->acl = $acl;
		$this->params = $params;
		
		$this->init();
	}
	
	protected function init() {}
	
}