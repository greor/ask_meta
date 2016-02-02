<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Settings extends Controller_Admin_Front {

	public function before()
	{
		parent::before();

// 		if ( ! IS_SUPER_USER) {
// 			$this->back_url = Route::url('admin', array( 
// 				'controller' => 'sites'
// 			));
// 			$this->request->redirect($this->back_url);
// 		}
	}

	public function action_index()
	{
		$this->title = __('Settings');

		$this->template
			->set_filename('settings/menu')
			->set('menu', Kohana::$config->load('admin/settings.menu') );
	}
} 
