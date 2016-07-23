<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Page extends Controller_Front {

	public function action_static()
	{
		$orm_helper = ORM_Helper::factory('page');
		$orm_helper->orm()
			->where('id', '=', $this->page_id)
			->find();
		
		$orm = $orm_helper->orm();
		if ( ! $orm->loaded()) {
			throw new HTTP_Exception_404;
		}
		if ($orm->site_id != SITE_ID) {
			$this->canonical_url_main_site();
		}
		
		$properties = $orm_helper->property_list();
		
		$this->template
			->set_filename('page')
			->set('orm', $orm)
			->set('properties', $properties);
		
		$this->title = $orm->title;
	}
	
	public function action_page()
	{
		$link = URL::base().Page_Route::dynamic_base_uri($this->request->page['data']);
		$this->request->current()
			->redirect($link);
	}

	public function action_url()
	{
		$this->request->current()
			->redirect($this->request->page['data']);
	}

} 