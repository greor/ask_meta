<?php defined('SYSPATH') or die('No direct access allowed.');

return array
(
	'theme' => 'default',
	'feedback_from' => 'no-reply@site.loc',
	'feedback_to'   => 'admin@site.loc',

	/*
	 * Свойства сайта
	 */
	'properties' => array(
		'channel_main' => 'channel',
		'channel_web1' => 'channel',
		'channel_web2' => 'channel',
		'channel_web3' => 'channel',
		'social_ok' => 'link',
		'social_vk' => 'link',
		'social_fb' => 'link',
		'social_ig' => 'link',
		'social_yt' => 'link',
	),
	
	/*
	 * Поддерживаемые дизайном соц. иконки
	 */
	'socnet_links' => array(
		'social_ok' => array(
			'title' => 'Одноклассники',
			'class' => 'social-ok',
		),
		'social_vk' => array(
			'title' => 'Вконтекте',
			'class' => 'social-vk',
		),
		'social_fb' => array(
			'title' => 'Facebook',
			'class' => 'social-fb',
		),
		'social_ig' => array(
			'title' => 'Instagram',
			'class' => 'social-ig',
		),
		'social_yt' => array(
			'title' => 'Youtube',
			'class' => 'social-yt',
		),
	),
	
	/*
	 * Список поддерживаемых каналов для сайта, указывается тип и привязка к плейлисту (см. конфиг playlist.php).
	 * Ключи массива - имена свойств сайта, в которых хранится информация каналов (название канала, адреса потоков, логотипы) 
	 * Поля:
	 * 		'static' - всегда использовать значение master-сайта (т.е. регионы не могу перегрузить адрес потока)
	 * 		'playlist_code' -  связывает канал с плейлистом - конфига плейлиста (см. конфиг playlist.php), поле 'code'.
	 */
	'channels' => array(
		'channel_main' => array(
			'static' => FALSE,
			'playlist_code' => 'main',
		),
		'channel_web1' => array(
			'static' => TRUE,
			'playlist_code' => 'web1',
		),
		'channel_web2' => array(
			'static' => TRUE,
			'playlist_code' => 'web2',
		),
		'channel_web3' => array(
			'static' => TRUE,
			'playlist_code' => 'web3',
		),
	),
	
	/*
	 * Определяет потоки (качество), используемых на сайте
	 */
	'streams' => array(
		'kbps-128', 'kbps-256'
	),
	
	/*
	 * Определяем ссылки для json-файлов onAir
	 */
	'onair' => array(
		array(
			'url' => '/json/onair.json',
			'site_id' => 1,
		),
	),
	
);
