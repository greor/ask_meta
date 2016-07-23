<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'admins' => array(
		'title' => __('Admin list'),
		'link' => Route::url('admin', array(
			'controller' => 'admins',
		)),
		'sub' => array()
	),
);
