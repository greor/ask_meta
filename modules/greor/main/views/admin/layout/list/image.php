<?php defined('SYSPATH') or die('No direct script access.');

	if ($value) {
		$img_size = getimagesize(DOCROOT.$orm_helper->file_path($field, $value));
			
		$src = $orm_helper->file_uri($field, $value);
		if ($img_size[0] > 100 OR $img_size[1] > 100) {
			$thumb = Thumb::uri('admin_image_100', $src);
		} else {
			$thumb = $src;
		}
			
		echo HTML::anchor($src, HTML::image($thumb), array(
			'class' => 'image-holder',
		));
	} else {
		echo __('No image');
	}