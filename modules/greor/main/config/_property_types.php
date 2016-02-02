<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'text' => array(
		'type' => 'text',
		'rules' => array(
		),
	),
	'string' => array(
		'type' => 'simple',
		'rules' => array(
			array('max_length', array(':value', 255)),
		),
	),
	'link' => array(
		'type' => 'simple',
		'rules' => array(
			array('max_length', array(':value', 255)),
			array('url'),
		),
	),
	'image' => array(
		'type' => 'file',
		'rules' => array(
			array('Ku_File::valid'),
			array('Ku_File::size', array(':value', '3M')),
			array('Ku_File::type', array(':value', 'jpg, jpeg, bmp, png, gif')),
		),
	),
	'channel_test' => array(
		'type' => 'structure',
		'fields' => array(
			'title' => 'string',
			'code' => 'string',
			'logo' => 'image',
			'kbps-64' => 'link',
			'kbps-128' => 'link',
			'kbps-256' => 'link',
			'kbps-320' => 'link',
			'hifi' => 'link',
			'format' => 'stream_format',
		),
	),
	'stream_format' => array(
		'type' => 'enum',
		'multiple' => FALSE,
		'set' => array(
			0 => array(
				'key' => 'mp3',
				'value' => 'MP3',
			),
			1 => array(
				'key' => 'ogg',
				'value' => 'OGG',
			),
		),
	),
);