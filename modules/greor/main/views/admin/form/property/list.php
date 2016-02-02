<?php defined('SYSPATH') or die('No direct access allowed.'); 

	foreach ($properties as $_title => $_item) {
	
		echo '<fieldset>';
		echo '<legend>', Helper_Property::label($_item['name']), '</legend>';
	
		echo View_Admin::factory('form/property/field', array(
			'item' => $_item,
			'title' => $_title
		));
	
		echo '</fieldset>';
	}