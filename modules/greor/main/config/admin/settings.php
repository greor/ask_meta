<?php defined('SYSPATH') or die('No direct access allowed.');

$return = array(
	'menu' => array()
);

if (IS_SUPER_USER) {
	$return['menu'][] = array(
		'url' => Route::url('admin', array( 
			'controller' => 'sites',
		)),
		'title' => __('Site manager'),
		'resource_name' => 'sites',
	);
} else {
	$return['menu'][] = array(
		'url' => Route::url('admin', array(
			'controller' => 'sites',
		)),
		'title' => __('Edit site'),
		'resource_name' => 'sites',
	);
}

$return['menu'][] = array(
	'url' => Route::url('admin', array( 
		'controller' => 'forms_structure',
	)),
	'title' => __('Forms'),
	'resource_name' => 'forms_structure',
);

return $return;