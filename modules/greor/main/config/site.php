<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'theme' => 'default',
	'feedback_from' => 'no-reply@site.loc',
	'feedback_to'   => 'admin@site.loc',

	/*
	 * Свойства сайта
	 */
	'properties' => array(),
	
	/*
	 * Список поддерживаемых каналов для сайта, указывается тип и привязка к плейлисту (см. конфиг playlist.php).
	 * Ключи массива - имена свойств сайта, в которых хранится информация каналов (название канала, адреса потоков, логотипы) 
	 * Поля:
	 * 		'static' - всегда использовать значение master-сайта (т.е. регионы не могу перегрузить адрес потока)
	 * 		'playlist_code' -  связывает канал с плейлистом - конфига плейлиста (см. конфиг playlist.php), поле 'code'.
	 */
	'channels' => array(
// 		'channel_main' => array(
// 			'static' => FALSE,
// 			'playlist_code' => 'main',
// 		),
// 		'channel_web1' => array(
// 			'static' => TRUE,
// 			'playlist_code' => 'web1',
// 		),
	),
	
);
