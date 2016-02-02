<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Forms_Responses extends Controller_Admin_Forms {

	public function action_index()
	{
		$orm = ORM::factory('form');
	
		$paginator_orm = clone $orm;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(20)
			->count($paginator_orm->count_all());
		unset($paginator_orm);
	
		$list = $orm
			->paginator($paginator)
			->find_all();
	
		$this->template
			->set_filename('forms/responses/list')
			->set('list', $list)
			->set('paginator', $paginator);
	
		if ($this->acl->is_allowed($this->user, $orm, 'add') ) {
			$this->left_menu_form_add();
		}
		
		$this->title = __('Forms (Responses)');
		$this->sub_title = __('List');
	}
	
	public function action_form()
	{
		$id = (int) Request::current()->param('id');
		$orm_form = ORM::factory('form', $id);
		if ( ! $orm_form->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		$paginator_orm = clone $orm_form->responses;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(20)
			->count($paginator_orm->count_all());
		unset($paginator_orm);
		
		$list = $orm_form->responses
			->paginator($paginator)
			->find_all();
		
		$this->template
			->set_filename('forms/responses/form')
			->set('orm_form', $orm_form)
			->set('list', $list)
			->set('paginator', $paginator);
		
		if ($this->acl->is_allowed($this->user, $orm_form, 'add') ) {
			$this->left_menu_form_add();
		}
		
		if ($this->acl->is_allowed($this->user, $orm_form, 'export_csv')) {
			$this->left_menu_csv_export($orm_form->id);
		}
		
		$this->title = __('Messages');
		$this->sub_title = __('Form').' [ '.$orm_form->title.' ]';
		
		$this->breadcrumbs[] = array(
			'title' => $this->title,
		);
	}

	public function action_view()
	{
		$request = $this->request->current();
		$id = (int) Request::current()->param('id');
		$orm = ORM::factory('form_Response', $id);
		if ( ! $orm->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		$orm_form = $orm->form;
		if ( ! $orm_form->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'owner' => $this->owner,
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('admin', array(
				'controller' => $this->controller_name['responses'],
				'action' => 'form',
				'id' => $orm_form->id,
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		try {
			$orm->new = 0;
			$orm->save();
		} catch (ORM_Validation_Exception $e) {}

		$this->template
			->set_filename('forms/responses/view')
			->set('orm', $orm);
		
		if ($this->acl->is_allowed($this->user, $orm_form, 'add') ) {
			$this->left_menu_form_add();
		}
		
		$this->left_menu_messages_list($orm_form->id);
			
		$this->title = __('Message');
		$this->sub_title = $orm->created;
		
		$this->breadcrumbs[] = array(
			'title' => __('Messages'),
			'link' => $this->back_url,
		);
		
		$this->breadcrumbs[] = array(
			'title' => $this->title,
		);
	}

	public function action_mark()
	{
		$request = $this->request->current();
		$id = (int) Request::current()->param('id');
		$orm = ORM::factory('form_Response', $id);
		if ( ! $orm->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		$orm_form = $orm->form;
		if ( ! $orm_form->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'owner' => $this->owner,
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('admin', array(
				'controller' => $this->controller_name['responses'],
				'action' => 'form',
				'id' => $orm_form->id,
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		try {
			$orm->new = 0;
			$orm->save();
		} catch (ORM_Validation_Exception $e) {}
		
		$request->redirect($this->back_url);
	}
	
	public function action_csv()
	{
		$this->auto_render = FALSE;
		
		$id = (int) Request::current()->param('id');
		$orm_form = ORM::factory('form', $id);
		if ( ! $orm_form->loaded()) {
			throw new HTTP_Exception_404();
		}
		
		$fields = array_flip(array(
			'id', 
			'email', 
			'text', 
			'created',
		));
		$labels = Arr::overwrite($fields, $orm_form->responses->labels());
		$list = $orm_form->responses
			->find_all();
		
		$queue = array();
		
		Database::instance()->set_charset('cp1251');
		
		foreach ($list as $_orm) {
			$_values = array_map('Helper_CSV::escape', $_orm->as_array());
			$_values = array_intersect_key($_values, $fields);
			$_item = array_combine($labels, $_values);
			$queue[] = array_filter($_item);
		}
		
		Database::instance()->set_charset('utf8');
		
		Helper_CSV::instance()->send($queue);
	}
	
	protected function _get_breadcrumbs()
	{
		$query_array = array(
			'owner' => $this->owner,
		);
		$breadcrumbs = array(
			array(
				'title' => __('Forms (Responses)'),
				'link' => Route::url('admin', array(
					'controller' => $this->controller_name['responses'],
					'query' => Helper_Page::make_query_string($query_array),
				)),
			)
		);
	
		return array_merge($breadcrumbs, $this->breadcrumbs);
	}
	
} 