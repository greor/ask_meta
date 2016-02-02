<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Widgets_Example extends Controller_Front_Widget {
	
	public function action_index()
	{
		$this->template
			->set_filename('widgets/template/example/list');
	}
	
}