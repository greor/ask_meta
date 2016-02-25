<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Forms_Structure extends Controller_Admin_Forms {

	protected function get_owner_list()
	{
		$list = Kohana::$config->load('forms.owner_list');
		$result = array();
		foreach ($list as $_k => $_v) {
			if ( ! $_v['active']) {
				continue;
			}
			$result[$_k] = __($_v['title']);
		}
		return $result;
	}
	
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
			->set_filename('forms/structure/list')
			->set('list', $list)
			->set('paginator', $paginator);
		
		if ($this->acl->is_allowed($this->user, $orm, 'add') ) {
			$this->left_menu_form_add();
		}
			
		$this->title = __('Forms (structure)');
		$this->sub_title = __('List');
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$this->form_id = $id = (int) $this->request->current()->param('id');
		$helper_orm = ORM_Helper::factory('form');
		$orm = $helper_orm->orm();
		if ( (bool) $id) {
			$orm
				->and_where('id', '=', $id)
				->find();
			if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit form');
		} else {
			if ( ! $this->acl->is_allowed($this->user, $orm, 'add')) {
				throw new HTTP_Exception_404();
			}
			
			$this->title = __('Add form');
		}
		
		if (empty($this->back_url)) {
			$query_array = array(
				'owner' => $this->owner,
			);
			$query_array = Paginator::query($request, $query_array);
			$this->back_url = Route::url('admin', array(
				'controller' => $this->controller_name['structure'],
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		
		if ($this->is_cancel) {
			$request->redirect($this->back_url);
		}
		
		$errors = array();
		$submit = Request::$current->post('submit');
		if ($submit) {
			try {
				if ($orm->loaded()) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
				} else {
					$orm->owner = $this->owner;
					$orm->site_id = SITE_ID;
					$orm->creator_id = $this->user->id;
				}
				
				$values = $this->request->current()->post();
				$values['public_date'] = $this->value_multiple_date($values, 'public_date');
				$values['close_date'] = $this->value_multiple_date($values, 'close_date');
				$helper_orm->save($values + $_FILES);
				
				$this->_save_rows($orm);
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}
		
		// If add action then $submit = NULL
		if ( ! empty($errors) OR $submit != 'save_and_exit')
		{
			if ( ! $orm->loaded()) {
				$orm->text_show_top = Kohana::$config->load('forms.default.text_show_top');
			}
			
			$helper_form = new Helper_Form();
			$fields = $helper_form->get_fields($orm);
			
			$this->template
				->set_filename('forms/structure/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('fields_types', Kohana::$config->load('_forms.type'))
				->set('fields_std', Kohana::$config->load('forms.fields_std'))
				->set('fields', $fields);
				
			if ($this->acl->is_allowed($this->user, $orm, 'add') ) {
				$this->left_menu_form_add();
			}
			
			$this->sub_title = __('Forms (structure)');
			
			$this->breadcrumbs[] = array(
				'title' => $this->title,
			);
		} else {
			$request->redirect($this->back_url);
		}
	}

	public function action_delete()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
	
		$helper_orm = ORM_Helper::factory('form');
		$orm = $helper_orm->orm();
		$orm
			->and_where('id', '=', $id)
			->find();
	
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		if (in_array($orm->owner, $this->not_deleted_owner)) {
			throw new HTTP_Exception_404();
		}
	
		if ($this->element_delete($helper_orm)) {
			if (empty($this->back_url)) {
				$query_array = array(
					'owner' => $this->owner,
				);
				$query_array = Paginator::query($request, $query_array);
				$this->back_url = Route::url('modules', array(
					'controller' => $this->controller_name['structure'],
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
	
			$request
				->redirect($this->back_url);
		}
	}
	
	private function _save_rows($orm)
	{
		$existed_db = $orm
			->fields
			->find_all()
			->as_array('id');
		
		$post = $this->request->current()->post('set');
		if (empty($post)) {
			$post = array();
		}
		$post = array_filter($post, create_function('$val','return ! empty($val["type"]);'));
		
		
		$update = array_intersect_key($post, $existed_db);
		$remove = array_diff_key($existed_db, $post);
		$insert = array_filter($post, create_function('$val','return strpos($val["id"], "n") === 0;'));
		unset($existed_db);
		
		$helper_orm = ORM_Helper::factory('form_Field');
		foreach ($remove as $_orm) {
			$helper_orm->orm($_orm);
			$this->element_delete($helper_orm);
		}
		unset($remove);
		
		foreach ($update as $_item) {
			$this->_save_field($_item + array(
				'form_id' => $orm->id
			), TRUE);
		}
		unset($update);
		
		foreach ($insert as $_item) {
			$this->_save_field($_item + array(
				'form_id' => $orm->id
			));
		}
		unset($insert);
		
		$helper_orm->position_fix('position');
	}

	private function _save_field($data, $update = FALSE)
	{
		$values = array(
			'form_id' => Arr::get($data, 'form_id'),
			'type' => Arr::get($data, 'type'),
			'position' => (int) Arr::get($data, 'position'),
			'title' => Arr::get($data, 'title'),
			'default' => Arr::get($data, 'default'),
			'required' => (bool) Arr::get($data, 'required'),
			'additional' => serialize(array()),
		);
		
		switch ($values['type']) {
			case 'text':
				if ( ! empty($data['email']) AND (bool) $data['email']) {
					$values['additional'] = serialize(array(
						'email' => TRUE
					));
				}
				break;
			case 'textarea':
				// Do nothing
				break;
			case 'checkbox':
				// Do nothing
				break;
			case 'select':
				$options = array();
				if ( ! empty($data['options']) AND is_array($data['options'])) {
					$_key = 0;
					foreach ($data['options'] as $_v) {
						$options[] = array(
							'id' => $_key++,
							'value' => $_v,
						);
					}
				}
				$values['additional'] = serialize(array(
					'options' => $options
				));
				break;
			case 'date':
				if ( ! empty($data['current_time']) AND (bool) $data['current_time']) {
					$values['additional'] = serialize(array(
						'current_time' => TRUE
					));
				}
				break;
			case 'counter':
				$range = array(
					'from' => 0,
					'to' => 999,
				);
				
				if ( ! empty($data['range']['from'])) {
					$range['from'] = (int) $data['range']['from'];
				}
				if ( ! empty($data['range']['to'])) {
					$range['from'] = (int) $data['range']['to'];
				}
				$values['additional'] = serialize(array(
					'range' => $range
				));
				break;
		}

		$errors = array();
		if ($update) {
			$orm = ORM::factory('form_Field', Arr::get($data, 'id'));
			if ( ! $orm->loaded()) {
				// Элемент для обновления не найден
				return $errors;
			}
		} else {
			$orm = ORM::factory('form_Field');
		}
		
		try {
			$helper_orm = ORM_Helper::factory('form_Field');
			$helper_orm->orm($orm);
			$helper_orm->save($values);
		} catch (ORM_Validation_Exception $e) {
			$errors = $this->errors_extract($e);
			
			var_dump($errors); die;
		}
		
		return $errors;
	}

	protected function _get_breadcrumbs()
	{
		$query_array = array(
			'owner' => $this->owner,
		);
		$breadcrumbs = array(
			array(
				'title' => __('Forms (structure)'),
				'link' => Route::url('admin', array(
					'controller' => $this->controller_name['structure'],
					'query' => Helper_Page::make_query_string($query_array),
				)),
			)
		);
		
		return array_merge($breadcrumbs, $this->breadcrumbs);
	}
	
}
