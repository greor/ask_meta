<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'forms_structure' => array(
		'title' => __('Forms (structure)'),
		'link' => Route::url('admin', array(
			'controller' => 'forms_structure',
		)),
		'sub' => array()
	),
	'forms_responses' => array(
		'title' => __('Forms (Responses)'),
		'link' => Route::url('admin', array(
			'controller' => 'forms_responses',
		)),
		'sub' => array()
	),
);
