<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Modules_Home extends Controller_Front {
	
	public function action_index()
	{
		$this->template
			->set_filename('modules/home/content');
	}
	
} 