<?php

return array(

	/*
	 * The Authentication library to use
	 * Make sure that the library supports:
	 * 1) A get_user method that returns FALSE when no user is logged in
	 *	  and a user object that implements Acl_Role_Interface when a user is logged in
	 * 2) A static instance method to instantiate a Authentication object
	 *
	 * array(CLASS_NAME,array $arguments)
	 */
	'lib' => array(
		'class'  => 'A1', // (or AUTH)
		'params' => array(
			'name' => 'admin/a1'
		)
	),

	/**
	 * Throws an exception when authorization fails.
	 */
	'exception' => FALSE,

	/**
	 * Exception class to throw when authorization fails (eg 'HTTP_Exception_401')
	 */
	'exception_type' => 'a2_exception',

	/*
	 * The ACL Roles (String IDs are fine, use of ACL_Role_Interface objects also possible)
	 * Use: ROLE => PARENT(S) (make sure parent is defined as role itself before you use it as a parent)
	 */
	'roles' => array
	(
		'guest' => NULL,
		'user' => 'guest',
		'base' => 'user',
		'full' => 'base',
		'main' => 'full',
		'super' => 'main',
	),

	/*
	 * The name of the guest role
	 * Used when no user is logged in.
	 */
	'guest_role' => 'guest',

	/*
	 * The ACL Resources (String IDs are fine, use of ACL_Resource_Interface objects also possible)
	 * Use: ROLE => PARENT (make sure parent is defined as resource itself before you use it as a parent)
	 */
	'resources' => array
	(
		// ADD YOUR OWN RESOURCES HERE
		//'blog'	=>	NULL

		'site_switcher' => NULL,
		'settings' => NULL,
		'pages' => NULL,
		'page' => NULL,
		'sites' => NULL,
		'site' => NULL,
		'admins' => NULL,
		'admin' => NULL,
		'modules' => NULL,
		'module' => NULL,
		'module_controller' => NULL,
	),

	/*
	 * The ACL Rules (Again, string IDs are fine, use of ACL_Role/Resource_Interface objects also possible)
	 * Split in allow rules and deny rules, one sub-array per rule:
	     array( ROLES, RESOURCES, PRIVILEGES, ASSERTION)
	 *
	 * Assertions are defined as follows :
			array(CLASS_NAME,$argument) // (only assertion objects that support (at most) 1 argument are supported
			                            //  if you need to give your assertion object several arguments, use an array)
	 */
	'rules' => array
	(
		'allow' => array
		(
			'no_category_option' => array(
				'role'  => 'super',
				'resource' => NULL,
				'privilege' => 'show_no_category',
			),

			'site_switcher' => array(
				'role' => 'super',
				'resource' => 'site_switcher',
				'privilege' => 'show',
			),

			'admins' => array(
				'role' => 'super',
				'resource' => 'admins',
				'privilege' => 'read',
			),
			'admin_edit' => array(
				'role' => 'super',
				'resource' => 'admin',
				'privilege' => 'edit',
			),

		
			'settings' => array(
				'role' => 'base',
				'resource' => 'settings',
				'privilege' => 'read',
			),
		
		
			'sites' => array(
				'role' => 'base',
				'resource' => 'sites',
				'privilege' => 'read',
			),
			'sites_del_1' => array(
				'role' => 'super',
				'resource'  => 'site',
				'privilege' => 'delete',
			),
			'sites_edit_1' => array(
				'role' => 'base',
				'resource' => 'site',
				'privilege' => 'edit',
				'assertion' => array('Acl_Assert_Argument', array( 
					'site_id' => 'id' 
				)),
			),
			'sites_edit_2' => array(
				'role' => 'main',
				'resource' => 'site',
				'privilege' => 'edit',
			),
		
			'sites_edit_type' => array(
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'edit_type',
			),
			'sites_edit_name' => array(
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'edit_name',
			),
			'sites_edit_code' => array(
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'edit_code',
			),
			'sites_edit_mmt' => array(
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'edit_mmt',
			),
			'sites_active_change' => array(
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'active_change',
			),

		
			'pages' => array(
				'role' => 'base',
				'resource' => 'pages',
				'privilege' => 'read',
			),
			'pages_edit_1' => array(
				'role' => 'base',
				'resource' => 'page',
				'privilege' => 'edit',
				'assertion' => array('Acl_Assert_Argument', array(
					'site_id' => 'site_id'
				)),
			),
			'pages_edit_2' => array(
				'role' => 'main',
				'resource' => 'page',
				'privilege' => 'edit',
			),
		
			'pages_fix_all' => array(
				'role' => 'super',
				'resource' => 'page',
				'privilege' => 'fix_all',
			),
			'pages_fix_master' => array(
				'role' => 'main',
				'resource' => 'page',
				'privilege' => 'fix_master',
			),
			'pages_fix_slave' => array(
				'role' => 'base',
				'resource' => 'page',
				'privilege' => 'fix_slave',
			),
			
			'pages_link_module' => array(
				'role' => 'full',
				'resource' => 'page',
				'privilege' => 'link_module',
				'assertion' => array('Acl_Assert_Argument', array( 
					'site_id' => 'site_id' 
				)),
			),
			'pages_for_all_change_1' => array(
				'role' => 'main',
				'resource' => 'page',
				'privilege' => 'for_all_change',
				'assertion' => array('Acl_Assert_Module'),
			),
			'pages_for_all_change_2' => array(
				'role' => 'super',
				'resource' => 'page',
				'privilege' => 'for_all_change',
			),

			'pages_can_hiding_change_1' => array(
				'role' => 'main',
				'resource' => 'page',
				'privilege' => 'can_hiding_change',
				'assertion'	=> array('Acl_Assert_Module'),
			),
			'pages_can_hiding_change_2'	=>	array(
				'role' => 'super',
				'resource' => 'page',
				'privilege' => 'can_hiding_change',
			),

			'pages_status_change_1'	=>	array(
				'role' => 'base',
				'resource' => 'page',
				'privilege' => 'status_change',
				'assertion' => array('Acl_Assert_Module'),
			),
			'pages_status_change_2' => array(
				'role' => 'super',
				'resource' => 'page',
				'privilege' => 'status_change',
			),
			'pages_page_type_change_1' => array(
				'role' => 'base',
				'resource' => 'page',
				'privilege' => 'page_type_change',
				'assertion'	=> array('Acl_Assert_Module'),
			),
			'pages_page_type_change_2' => array(
				'role' => 'super',
				'resource' => 'page',
				'privilege' => 'page_type_change',
			),
				
			
			
			
			
			'forms_structure' => array(
				'role' => 'full',
				'resource' => 'forms_structure',
				'privilege' => 'read',
			),
			'forms_responses' => array(
				'role' => 'full',
				'resource' => 'forms_responses',
				'privilege' => 'read',
			),
			'forms_add' => array(
				'role' => 'full',
				'resource' => 'form',
				'privilege' => 'add',
			),
			'forms_edit_1' => array(
				'role' => 'super',
				'resource' => 'form',
				'privilege' => 'edit',
			),
			'forms_edit_2' => array(
				'role' => 'full',
				'resource' => 'form',
				'privilege' => 'edit',
				'assertion' => array('Acl_Assert_Argument', array(
					'site_id' => 'site_id'
				)),
			),
			'forms_export_csv' => array(
				'role' => 'full',
				'resource' => 'form',
				'privilege' => 'export_csv',
				'assertion' => array('Acl_Assert_Argument', array(
					'site_id' => 'site_id'
				)),
			),
			
			
			
/*
 *	Config below can't use there, because uses constant, that definded after this config loading
 *	See inject this in Controller_Admin_Structure
 *
 */
//			'structure_can_hide'	=>	array(
//				'role'      => 'full',
//				'resource'  => 'page',
//				'privilege' => 'can_hide',
//				'assertion'	=> array( 'Acl_Assert_Site', array(
//											'site_id' => SITE_ID,
//											'site_id_master' => SITE_ID_MASTER
//								)),
//			),

			'modules_list'	=>	array(
				'role'      => 'base',
				'resource'  => 'modules',
				'privilege' => 'read',
			),

		),
		'deny' => array
		(
			// ADD YOUR OWN DENY RULES HERE
		)
	)
);