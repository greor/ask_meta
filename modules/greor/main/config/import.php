<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'enable' => FALSE,
	'database' => array(
		'type' => 'mysql',
		'connection' => array(
			'hostname' => '{HOST}',
			'database' => '{DB}',
			'username' => '{USER}',
			'password' => '{PASS}',
			'persistent' => FALSE,
		),
		'table_prefix' => '',
		'charset' => 'utf8',
		'caching' => FALSE,
		'profiling' => FALSE,
	),
);
