<?php defined('SYSPATH') or die('No direct script access.');

	if (empty($paginator)) {
		return;
	}
	
	echo '<ul>';
	
	if ( ! empty($paginator['previous'])) {
		echo '<li>', HTML::anchor($paginator['previous'], '&larr;'), '</li>';
	} else {
		echo '<li class="disabled">&larr;</li>';
	}
	
	foreach ($paginator['items'] as $_item) {
		if ( ! empty($_item['current'])) {
			echo '<li class="active">';
		} else {
			echo '<li>';
		}
		echo HTML::anchor($_item['link'], $_item['title']), '</li>';
	}
	
	if ( ! empty($paginator['next'])) {
		echo '<li>', HTML::anchor($paginator['next'], '&rarr;'), '</li>';
	} else {
		echo '<li class="disabled">&rarr;</li>';
	}
	
	echo '</ul>';	
