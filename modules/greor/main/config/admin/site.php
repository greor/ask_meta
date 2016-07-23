<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'theme' => 'admin',
	'assets' => '/assets/admin/',
	'logo'		=>	array(
		'src'	=>	'img/logo.png',
		'title'	=>	'Sitename',
	),

	'reserved_routes' => array(
		'modules', 'admin', 'admin_error'
	),

// 	'help_link'	=>	'/guide0000.pdf',
// 	'help_title'	=>	__('Help'),
);