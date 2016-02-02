<?php defined('SYSPATH') or die('No direct script access.'); 

	if (empty($BREADCRUMBS)) {
		return;
	}
	
	echo '<ol>';

	$last = array_pop($BREADCRUMBS);
	foreach ($BREADCRUMBS as $_item) {
		echo '<li>', HTML::anchor($_item['link'], $_item['title']), "</li>";
	}
	
	if (is_array($last)) {
		echo '<li class="active">', HTML::chars($last['title']), '</li>';
	}
	
	echo '</ol>';
	