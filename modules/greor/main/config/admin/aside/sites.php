<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'sites' => array(
		'title' => __('Site manager'),
		'link' => Route::url('admin', array(
			'controller' => 'sites',
		)),
		'sub' => array()
	),
);
