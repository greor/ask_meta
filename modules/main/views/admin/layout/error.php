<?php defined('SYSPATH') or die('No direct access allowed.');

	if (empty($errors) OR ! is_array($errors)) {
		return;
	} 
	
	echo '<div class="alert alert-error">';
	foreach ($errors as $key => $error) {
		echo '<p>'.$error.'</p>';
	}
	echo '</div>';
