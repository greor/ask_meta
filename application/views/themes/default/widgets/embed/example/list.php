<?php defined('SYSPATH') or die('No direct script access.'); 

	$query_array = array(
		'limit' => 3,
	);

	$link = Route::url('widgets', array(
		'controller' => 'example',
		'query' => http_build_query($query_array),
	));

	echo Request::factory($link)
		->execute()
		->body();
	