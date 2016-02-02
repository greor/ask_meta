<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	// значения полей конструктора по-умолчанию
	'default' => array(
		'text_show_top' => TRUE
	),
	
	// значения поля 'owner' для форм, которые удалить (формы) 
	'not_deleted_owner' => array(),
	
	// список возможных значений поля 'owner' (определяет связь, с каким модулем связана форма)
	'owner_list' => array(
		'feedback' => array(
			'title' => 'Feedback module',
			'active' => TRUE,
		)
	),
	
	// стандартные поля для конструктора форм
	// (создаются при нажатии на кнопку добавления стандартных полей)
	'fields_std' => array(
		array(
			'title' => 'ФИО',
			'required' => TRUE,
			'type' => 'text',
			'init' => TRUE,
		),
		array(
			'title' => 'Город',
			'required' => TRUE,
			'type' => 'text',
			'init' => TRUE,
		),
		array(
			'title' => 'E-mail',
			'required' => TRUE,
			'type' => 'text',
			'email' => TRUE,
			'init' => TRUE,
		),
		array(
			'title' => 'Телефон',
			'required' => TRUE,
			'type' => 'text',
			'init' => TRUE,
		),
	),
);