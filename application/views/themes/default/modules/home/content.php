<?php defined('SYSPATH') or die('No direct script access.'); 

	echo View_Theme::factory('modules/home/blocks/promo', array(
		'promo' => $promo
	));
	
	echo View_Theme::factory('widgets/embed/program/list');
	
	echo View_Theme::factory('widgets/embed/news/list', array(
		'home' => TRUE
	));
	
	echo View_Theme::factory('widgets/embed/articles/list');
	
	echo View_Theme::factory('modules/home/blocks/programs', array(
		'programs' => $programs
	));