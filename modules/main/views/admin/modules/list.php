<?php defined('SYSPATH') or die('No direct access allowed.'); 
	
	$i = 1; 
	echo '<ul class="unstyled mudules-list">';
	foreach ($modules as $_item):
		echo '
			<li>
				<span class="badge">'.str_pad($i, 2, '0', STR_PAD_LEFT).'</span>&nbsp;&nbsp;&nbsp;', HTML::anchor($_item['url'], $_item['name']), '
			</li>
		';
		$i++;
	endforeach;
	echo '</ul>';
