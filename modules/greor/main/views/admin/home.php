<?php defined('SYSPATH') or die('No direct access allowed.'); ?>

	<div class="span9">
		<div id="main">
			<div class="pull-right">
<?php 
			if ( ! empty($logo['src'])) {
				echo HTML::image($ASSETS.$logo['src'], array(
					'alt' => $logo['title']
				));
			} else {
				echo '<div>', HTML::chars($logo['title']), '</div>';
			}
?>
			</div>
		</div>
	</div>
