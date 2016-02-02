<?php defined('SYSPATH') or die('No direct access allowed.'); 
	
	if (empty($menu)) {
		return;
	}

	echo '<ul class="unstyled">';
	foreach ($menu as $_item) {
		if ( ! $ACL->is_allowed($USER, $_item['resource_name'], 'read')) {
			continue;
		}
		echo '<li style="line-height: 30px;">', HTML::anchor($_item['url'], $_item['title']), '</li>';
	}
	echo '</ul>';

