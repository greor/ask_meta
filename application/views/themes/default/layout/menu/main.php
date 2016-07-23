<?php defined('SYSPATH') or die('No direct script access.'); 

	if (empty($menu)) {
		return;
	}
	
	echo '<ul>';
	foreach ($menu as $_item) {
		$_sub = '';
		if ( ! empty($_item['sub'])) {
			foreach ($_item['sub'] as $_i) {
				$_sub .= '<li>'.HTML::anchor($_i['uri'], $_i['title']).'</li>';
			}
		}
		echo '<li>', HTML::anchor($_item['uri'], $_item['title']), '<ul>', $_sub, '</ul></li>';
	}
	echo '</ul>';
	
