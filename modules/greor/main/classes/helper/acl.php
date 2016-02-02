<?php defined('SYSPATH') or die('No direct script access.');

class Helper_ACL {
	
	private $_acl;
	
	public function __construct(A2 $acl)
	{
		$this->_acl = $acl;
	}
	
	public function inject($conf)
	{
		$acl = $this->_acl;
		// Resources
		if ( isset($conf['resources'])) {
			foreach ( $conf['resources'] as $resource => $parent) {
				$acl->add_resource($resource,$parent);
			}
		}
		
		// Rules
		if ( isset($conf['rules'])) {
			foreach ( array('allow','deny') as $method) {
				if ( isset($conf['rules'][$method])) {
					foreach ( $conf['rules'][$method] as $rule) {
						$role = $resource = $privilege = $assertion = NULL;
						extract($rule);
						if ($assertion) {
							if (is_array($assertion)) {
								$assertion = count($assertion) === 2
								? new $assertion[0]($assertion[1])
								: new $assertion[0];
							} else {
								$assertion = new $assertion;
							}
						}
						if ( $method === 'allow') {
							$acl->allow($role,$resource,$privilege,$assertion);
						} else {
							$acl->deny($role,$resource,$privilege,$assertion);
						}
					}
				}
			}
		}
	}
	
}