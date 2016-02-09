<?php defined('SYSPATH') or die('No direct access allowed.');

return array (
	'resources' => array(
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
	'rules' => array(
		'allow' => array (
			
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
				'role' => 'super',
				'resource' => 'site',
				'privilege' => 'edit',
			),
			'sites_edit_2' => array(
				'role' => 'base',
				'resource' => 'site',
				'privilege' => 'edit',
				'assertion' => array('Acl_Assert_Site_Edit', array(
					'site_id' => SITE_ID,
				)),
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
				'assertion' => array('Acl_Assert_Edit', array(
					'site_id' => SITE_ID,
				)),
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
				'assertion' => array('Acl_Assert_Edit', array(
					'site_id' => SITE_ID,
				)),
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
				'role' => 'full',
				'resource' => 'form',
				'privilege' => 'edit',
				'assertion' => array('Acl_Assert_Edit', array(
					'site_id' => SITE_ID,
				)),
			),
			'forms_export_csv' => array(
				'role' => 'full',
				'resource' => 'form',
				'privilege' => 'export_csv',
				'assertion' => array('Acl_Assert_Edit', array(
					'site_id' => SITE_ID,
				)),
			),
				
			'modules_list'	=>	array(
				'role' => 'base',
				'resource' => 'modules',
				'privilege' => 'read',
			),
		),
		'deny' => array()
	)
);