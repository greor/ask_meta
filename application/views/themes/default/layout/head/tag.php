<?php defined('SYSPATH') or die('No direct script access.');

	if ( ! empty($HEAD_TAGS)) {
		foreach ($HEAD_TAGS as $item) {
			$_attr = HTML::attributes($item['attr']);
			echo "\t<{$item['tag']} {$_attr}>\r\n";
		}
	}