<?php defined('SYSPATH') or die('No direct script access.');

	if ($ACL->is_allowed($USER, $orm, 'hide')) {
		if (in_array($orm->id, $hided_list)) {
			$_title = __('Show');
			$_icon = 'icon-eye-close';
			$_link = str_replace('{id}', $orm->id, $visibility_on_tpl);
		} else {
			$_title = __('Hide');
			$_icon = 'icon-eye-open';
			$_link = str_replace('{id}', $orm->id, $visibility_off_tpl);
		}
	
		echo HTML::anchor($_link, '<i class="'.$_icon.'"></i> ', array(
			'title' => $_title,
			'class' => 'btn hide_button'
		));
	}
