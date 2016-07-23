<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Admins extends Controller_Admin_Front {

	protected $sub_title = 'Users';
	protected $_exclude_admins = array('superadmin');

	protected function layout_aside()
	{
		$menu_items = array_merge_recursive(
			Kohana::$config->load('admin/aside/admins')->as_array(),
			$this->menu_left_ext
		);
		
		return parent::layout_aside()
			->set('menu_items', $menu_items);
	}

	public function action_index()
	{
		$orm = ORM::factory('admin')
			->where('delete_bit', '=', 0)
			->and_where('username', 'NOT IN', $this->_exclude_admins);

		if (in_array($this->user->role, array('main', 'super'))) {
			$orm->where('site_id', '=', SITE_ID);
		} else {
			$orm->where('site_id', '=', $this->user->site_id);
		}

		$paginator_orm = clone $orm;
		$paginator = new Paginator('admin/layout/paginator');
		$paginator
			->per_page(10)
			->count( $paginator_orm->count_all() );
		unset($paginator_orm);

		$list = $orm
			->paginator($paginator)
			->find_all();
			
		$this->template
			->set_filename('admins/list')
			->set('list', $list)
			->set('roles', $this->acl_roles())
			->set('paginator', $paginator);
		
		$this->title = __('Admin list');
		
		if ($this->acl->is_allowed($this->user, $orm, 'edit')) {
			$this->left_menu_user_add();
		}
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$id = (int) Request::current()->param('id');
		$helper_orm = ORM_Helper::factory('admin');
		$orm = $helper_orm->orm();
		
		if ( ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		} else {
			$this->left_menu_user_add();
		}
		
		if ( (bool) $id) {
			$orm
				->and_where('id', '=', $id)
				->find();
		
			if ( ! $orm->loaded() OR in_array($orm->username, $this->_exclude_admins)) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit admin');
		} else {
			$this->title = __('Add admin');
		}

		if (empty($this->back_url)) {
			$this->back_url = Route::url('admin', array(
				'controller' => 'admins',
			));
		}
		if ($this->is_cancel) {
			$request->redirect($this->back_url);
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

				$values = $request->post();

				if ( ! empty($values['password'])) {
					$ex_validation = Model_Admin::get_password_validation($values);
				} else {
					$ex_validation = NULL;
					unset($values['password']);
				}

				$helper_orm->save($values , $ex_validation);
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}

		// If add action then $submit = NULL
		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			$sites = ORM::factory('site')
				->find_all()
				->as_array('id', 'name');

			$this->template
				->set_filename('admins/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('roles', $this->acl_roles())
				->set('sites', $sites);
		} else {
			$request->redirect($this->back_url);
		}
	}

	public function action_delete()
	{
		$id = (int) Request::current()->param('id');
		$helper_orm = ORM_Helper::factory('admin', $id);
		$orm = $helper_orm->orm();
		
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
			throw new HTTP_Exception_404();
		}
		if (in_array($orm->username, $this->_exclude_admins)) {
			throw new HTTP_Exception_404();
		}
		
		if ($this->element_delete($helper_orm)) {
			if (empty($this->back_url)) {
				$this->back_url = Route::url('admin', array(
					'controller' => 'admins',
				));
			}
			$this->request->current()
				->redirect($this->back_url);
		}
	}

	protected function left_menu_user_add()
	{
		$this->menu_left_add(array(
			'admins' => array(
				'sub' => array(
					'add_admin' => array(
						'title' => __('Add admin'),
						'link' => Route::url('admin', array(
							'controller' => 'admins',
							'action' => 'edit'
						)),
					),
				),
			),
		));
	}

} 
