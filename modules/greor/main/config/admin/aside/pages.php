<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'pages' => array(
		'title' => __('Site structure'),
		'link' => Route::url('admin', array(
			'controller' => 'pages'
		)),
		'sub' => array(
			'add' => array(
				'title' => __('Add page'),
				'link' => Route::url('admin', array(
					'controller' => 'pages',
					'action' => 'edit'
				)),
			),
			'fix' => array(
				'title' => __('Fix positions'),
				'link' => Route::url('admin', array(
					'controller' => 'pages',
					'action' => 'position',
					'query' => 'mode=fix',
				)),
			),
		),
	),
	'clear_cache' => array(
		'title' => __('Clear structure cache'),
		'link' => Route::url('admin', array(
			'controller' => 'pages',
			'action' => 'clear_cache',
		)),
	),
);