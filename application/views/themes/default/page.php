<?php defined('SYSPATH') or die('No direct script access.'); ?>
	<h1><?php echo HTML::chars($orm->title); ?></h1>
<?php 
	echo $orm->text; 
