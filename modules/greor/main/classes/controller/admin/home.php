<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Home extends Controller_Admin_Front {

	public function action_index()
	{
		$this->title = __('Administrative Interface');
		$this->template
			->set_filename('home')
			->set('logo', $this->config['logo']);
	}
} 
