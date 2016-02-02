<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Sites extends Controller_Admin_Front {

	protected $menu_active_item = 'settings';
	protected $sub_title = 'Sites';

	public function before()
	{
		parent::before();

		if (IS_SUPER_USER) {
			ORM_Base::$site_id = NULL;
		} else {
			$this->request->current()
				->set_param('id', SITE_ID);
			$this->request->current()
				->action('edit');
		}
	}

	protected function layout_aside()
	{
		$menu_items = array_merge_recursive(
			Kohana::$config->load('admin/aside/sites')->as_array(),
			$this->menu_left_ext
		);

		return parent::layout_aside()
			->set('menu_items', $menu_items);
	}

	public function action_index()
	{
		$orm = ORM::factory('site');

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
			->set_filename('sites/list')
			->set('paginator', $paginator)
			->set('list', $list);
		
		$this->title = __('Sites list');
		
		if ($this->acl->is_allowed($this->user, $orm, 'edit')) {
			$this->left_menu_site_add();
		}
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$helper_orm = ORM_Helper::factory('site');
		$orm = $helper_orm->orm();
		
		if ( (bool) $id) {
			$orm
				->and_where('id', '=', $id)
				->find();
		
			$this->title = __('Edit site');
			$this->sub_title = $orm->name;
		} else {
			$this->title = __('Add site');
			$this->sub_title = __('New site');
		}
		
		if ( ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		} else {
			$this->left_menu_site_add();
		}
		
		if (empty($this->back_url)) {
			$query_array = Paginator::query($request);
			$this->back_url = Route::url('admin', array(
				'controller' => 'sites',
				'query' => Helper_Page::make_query_string($query_array),
			));
		}
		if ($this->is_cancel) {
			$request
				->redirect($this->back_url);
		}
		
		$errors = array();
		$submit = Request::$current->post('submit');
		if ($submit) {
			try {
				if ( (bool) $id) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
				} else {
					$orm->creator_id = $this->user->id;
				}
				
				$values = $this->meta_seo_reset(
					$this->request->current()->post(),
					'meta_tags'
				);

				if ($orm->id == SITE_ID_MASTER OR ! $this->acl->is_allowed($this->user, $orm, 'edit_type')) {
					unset($values['type']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'active_change')) {
					unset($values['active']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'edit_name')) {
					unset($values['name']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'edit_code')) {
					unset($values['code']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'edit_mmt')) {
					unset($values['mmt']);
				}
				
				$helper_orm->save($values + $_FILES);
				
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}

		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			
			$properties = $helper_orm->property_list();
			
			$this->template
				->set_filename('sites/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('properties', $properties);
		} else {
			$request->redirect($this->back_url);
		}

	}

	public function action_delete()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$helper_orm = ORM_Helper::factory('site', $id);
		$orm = $helper_orm->orm();
		
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		
		if ($this->element_delete($helper_orm)) {
			if (empty($this->back_url)) {
				$query_array = Paginator::query($request);
				$this->back_url = Route::url('admin', array(
					'controller' => 'sites',
					'query' => Helper_Page::make_query_string($query_array),
				));
			}
			$request
				->redirect($this->back_url);
		}
	}
	
	protected function left_menu_site_add()
	{
		$this->menu_left_add(array(
			'sites' => array(
				'sub' => array(
					'add' => array(
						'title' => __('Add site'),
						'link' => Route::url('admin', array(
							'controller' => 'sites',
							'action' => 'edit'
						)),
					),
				),
			),
		));
	}

} 
