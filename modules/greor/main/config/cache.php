<?php defined('SYSPATH') or die('No direct script access.');

$config = array (
	'file' => array(
		'driver' => 'file',
		'cache_dir' => APPPATH.'cache/other',
		'default_expire' => 3600,
		'ignore_on_delete' => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'struct' => array(
		'driver' => 'file',
		'cache_dir' => APPPATH.'cache/struct',
		'default_expire' => 3600,
		'ignore_on_delete' => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'page-helper' => array(
		'driver' => 'file',
		'cache_dir' => APPPATH.'cache/page-helper',
		'default_expire' => 3600,
		'ignore_on_delete' => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'sites' => array(
		'driver' => 'file',
		'cache_dir' => APPPATH.'cache/sites',
		'default_expire' => 3600,
		'ignore_on_delete' => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	'properties' => array(
		'driver' => 'file',
		'cache_dir' => APPPATH.'cache/properties',
		'default_expire' => 3600,
		'ignore_on_delete' => array(
			'.gitignore',
			'.git',
			'.svn'
		)
	),
	
// 	'banners'    => array(
// 		'driver'             => 'file',
// 		'cache_dir'          => APPPATH.'cache/banners',
// 		'default_expire'     => 3600,
// 		'ignore_on_delete'   => array(
// 			'.gitignore',
// 			'.git',
// 			'.svn'
// 		)
// 	),
// 	'schedule'    => array(
// 		'driver'             => 'file',
// 		'cache_dir'          => APPPATH.'cache/schedule',
// 		'default_expire'     => Date::DAY,
// 		'ignore_on_delete'   => array(
// 			'.gitignore',
// 			'.git',
// 			'.svn'
// 		)
// 	),
);

foreach ($config as $item) {
	if ( $item['driver'] === 'file' AND ! is_dir($item['cache_dir'])) {
		Ku_Dir::make_writable($item['cache_dir']);
	}
}

return $config;