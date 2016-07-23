<?php defined('SYSPATH') or die('No direct script access.');

	if ( ! empty($first_tpl)) {
		echo '<li>', HTML::anchor(str_replace('{id}', $orm->id, $first_tpl), '<i class="icon-chevron-up"></i> '.__('Move first'), array(
			'title' => __('Move first'),
		)), '</li>';
	}
	if ( ! empty($up_tpl)) {
		echo '<li>', HTML::anchor(str_replace('{id}', $orm->id, $up_tpl), '<i class="icon-arrow-up"></i> '.__('Move up'), array(
			'title' => __('Move up'),
		)), '</li>';
	}
	if ( ! empty($down_tpl)) {
		echo '<li>', HTML::anchor(str_replace('{id}', $orm->id, $down_tpl), '<i class="icon-arrow-down"></i> '.__('Move down'), array(
			'title' => __('Move down'),
		)), '</li>';
	}
	if ( ! empty($last_tpl)) {
		echo '<li>', HTML::anchor(str_replace('{id}', $orm->id, $last_tpl), '<i class="icon-chevron-down"></i> '.__('Move last'), array(
			'title' => __('Move last'),
		)), '</li>';
	}
		
	echo '<li class="divider"></li>';