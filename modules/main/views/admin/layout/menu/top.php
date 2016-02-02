<?php defined('SYSPATH') or die('No direct access allowed.'); 

	if (empty($menu)) {
		return;
	}
?>
	<ul class="nav">
<?php
	foreach ($menu as $_item) {
		echo '<li class="', $_item['class'], '">', HTML::anchor($_item['uri'], $_item['title']), '</li>';
	}
?>	
	</ul>
