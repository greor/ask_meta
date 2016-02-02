<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Pages extends Controller_Admin_Front {

	protected $sub_title = 'Site structure';
	
	public function before()
	{
		parent::before();

		// в конфиг a2 нельзя, т.к. используются константы SITE_ID и SITE_ID_MASTER,
		// которые определяются после подключения всех конфигов
		$inject_config = array(
			'rules' => array(
				'allow' => array(
					'pages_can_hide' => array(
						'role' => 'base',
						'resource' => 'page',
						'privilege' => 'can_hide',
						'assertion' => array('Acl_Assert_Page', array(
							'site_id' => SITE_ID,
							'site_id_master' => SITE_ID_MASTER
						)),
					),
				)
			)
		);
		
		$helper_acl = new Helper_ACL($this->acl);
		$helper_acl->inject($inject_config);
	}

	public function layout_aside()
	{
		$menu_items = Kohana::$config->load('admin/aside/pages')
			->as_array();
		
		return parent::layout_aside()
				->set('menu_items', $menu_items);
	}

	public function action_index()
	{
		$pages = ORM::factory('page')
			->order_by('level', 'asc')
			->order_by('position', 'asc')
			->find_all()
			->as_array('id');

		$hided_list = ORM::factory('hided_List')
			->where('object_name', '=', ORM::factory('page')->object_name())
			->find_all()
			->as_array(NULL, 'element_id');
			
		$this->template
			->set_filename('pages/list')
			->set('pages', $this->_parse_to_tree($pages))
			->set('base_uri_list', Helper_Page::parse_to_base_uri($pages) )
			->set('modules', Helper_Module::modules())
			->set('hided_list', $hided_list);
		
		$this->title = __('Site structure');
		$this->sub_title = __('Pages list');
	}

	public function action_edit()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$helper_orm = ORM_Helper::factory('page');
		$orm = $helper_orm->orm();
		
		if ( (bool) $id) {
			$orm
				->and_where('id', '=', $id)
				->find();

			if ( ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
			$this->title = __('Edit page');
		} else {
			$this->title = __('Add page');
		}
		
		if (empty($this->back_url)) {
			$this->back_url = Route::url('admin', array(
				'controller' => 'pages',
			));
		}
		if ($this->is_cancel) {
			$request
				->redirect($this->back_url);
		}
			
		$pages_db = ORM::factory('page')
			->order_by('level', 'asc')
			->order_by('parent_id', 'asc')
			->order_by('position', 'asc')
			->find_all();
		$pages = $pages_db->as_array('id');

		$errors = array();
		$submit = $request->post('submit');
		if ($submit) {
			try {
				if ( (bool) $id) {
					$orm->updater_id = $this->user->id;
					$orm->updated = date('Y-m-d H:i:s');
				} else {
					$orm->site_id = SITE_ID;
					$orm->creator_id = $this->user->id;
				}

				$parent_id = (int) $request->post('parent_id');
				if ($parent_id > 0 AND isset($pages[ $parent_id ])) {
					$orm->level = $pages[ $parent_id ]->level + 1;
				} else {
					$orm->level = Model_Page::LEVEL_START;
				}

				$values = $this->meta_seo_reset(
					$this->request->current()->post(),
					'meta_tags'
				);

				if (empty($values['uri']) OR row_exist($orm, 'uri', $values['uri'])) {
					$values['uri'] = transliterate_unique($values['title'], $orm, 'uri', array(
						array('and', 'parent_id', '=', $parent_id)
					));
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'for_all_change')) {
					unset($values['for_all']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'can_hiding_change')) {
					unset($values['can_hiding']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'status_change')) {
					unset($values['status']);
				}
				if ( ! $this->acl->is_allowed($this->user, $orm, 'page_type_change')) {
					unset($values['type']);
					unset($values['data']);
				}

				$helper_orm->save($values + $_FILES);

				Helper_Page::clear_cache();
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
			}
		}

		if ( ! empty($errors) OR $submit != 'save_and_exit') {
			$relations = $pages_db
				->as_array('id', 'parent_id');
			
			$modules = array();
			$linked_modules = Helper_Module::linked_modules();
			$leave_module_types = array(
				Helper_Module::MODULE_SINGLE, 
				Helper_Module::MODULE_STANDALONE
			);
			$this_page = $helper_orm->orm()
				->as_array();
			
			foreach (Helper_Module::modules() as $key => $value) {
				$_own_module = ($this_page['type'] == 'module' AND $this_page['data'] == $key);
				if (
					in_array($key, $linked_modules) 
					AND in_array($value['type'], $leave_module_types)
					AND ! $_own_module
				) {
					continue;
				}
				$modules[ $key ] = __( $value['name'] );
			}

			if ( (bool) $id) {
				$page_list = array_diff_key(
					Helper_Page::parse_to_list($pages),
					array_flip( $this->_get_childrens($id, $relations) )
				);
			} else {
				$page_list = Helper_Page::parse_to_list($pages);
			}

			$properties = $helper_orm->property_list();
			
			$this->template
				->set_filename('pages/edit')
				->set('errors', $errors)
				->set('helper_orm', $helper_orm)
				->set('pages', $page_list)
				->set('base_uri_list', Helper_Page::parse_to_base_uri($pages))
				->set('modules', $modules)
				->set('properties', $properties);
		} else {
			$request
				->redirect($this->back_url);
		}
	}

	public function action_delete()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
	
		$helper_orm = ORM_Helper::factory('page');
		$orm = $helper_orm->orm();
		$orm
			->and_where('id', '=', $id)
			->find();
	
		$has_module = ! Helper_Page::instance()
			->not_equal($orm, 'type', 'module');			
		$has_name = ! empty($helper_orm->orm()->name);
			
		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit') OR $has_module OR $has_name) {
			throw new HTTP_Exception_404();
		}
			
		if ($this->element_delete($helper_orm)) {
			Helper_Page::clear_cache();
	
			if (empty($this->back_url)) {
				$this->back_url = Route::url('admin', array(
					'controller' => 'pages',
				));
			}
			$request
				->redirect($this->back_url);
		}
	}

	public function action_position()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$mode = $request->query('mode');

		$errors = array();
		try {
			if ( $mode !== 'fix' ) {
				$helper_orm = ORM_Helper::factory('page', $id);
				$orm = $helper_orm->orm();
				if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
					throw new HTTP_Exception_404();
				}
				
				switch ($mode) {
					case 'up':
						$helper_orm
							->position_move('position', ORM_Position::MOVE_PREV);
						break;
					case 'down':
						$helper_orm
							->position_move('position', ORM_Position::MOVE_NEXT);
						break;
				}
			} else  {
				$helper_orm = ORM_Helper::factory('page');
				$orm = $helper_orm->orm();
				if ($this->acl->is_allowed($this->user, $orm, 'fix_all')) {
					$helper_orm
						->position_fix('position', ORM_Helper::POSITION_ALL);
				} elseif ($this->acl->is_allowed($this->user, $orm, 'fix_master')) {
					$helper_orm
						->position_fix('position', ORM_Helper::POSITION_MASTER);
				} elseif ($this->acl->is_allowed($this->user, $orm, 'fix_slave')) {
					$helper_orm
						->position_fix('position', ORM_Helper::POSITION_SLAVE);
				}
			}

			Helper_Page::clear_cache();
		} catch (ORM_Validation_Exception $e) {
			$errors = $e->errors( TRUE );
			$this->template
				->set_filename('layout/error')
				->set('errors', $errors)
				->set('title', __('Error'));
		}

		if (empty($errors)) {
			if (empty($this->back_url)) {
				$this->back_url = Route::url('admin', array(
					'controller' => 'pages',
				));
			}
			$request
				->redirect($this->back_url);
		}

	}

	public function action_element_visibility()
	{
		$request = $this->request->current();
		$id = (int) $request->param('id');
		$mode = $request->query('mode');

		$orm = ORM::factory('page')
			->and_where('id', '=', $id)
			->find();

		if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'can_hide')) {
			throw new HTTP_Exception_404();
		}

		if ($mode == 'hide') {
			$this->element_hide($orm->object_name(), $orm->id);
		} elseif ($mode == 'show') {
			$this->element_show($orm->object_name(), $orm->id);
		}

		if (empty($this->back_url)) {
			$this->back_url = Route::url('admin', array(
				'controller' => 'pages',
			));
		}
		$request
			->redirect($this->back_url);
	}

	public function action_clear_cache()
	{
		Helper_Page::clear_cache();

		if (empty($this->back_url)) {
			$this->back_url = Route::url('admin', array(
				'controller' => 'pages',
			));
		}
		
		$this->request->current()
			->redirect($this->back_url);
	}

	private function _parse_to_tree($page_list)
	{
		$childrens = array();

		foreach ($page_list as $item) {
			$childrens[ $item->id ] = array();

			if (isset($childrens[ $item->parent_id ])) {
				$childrens[ $item->parent_id ][] = $item->id;
			}
		}

		$return = array();
		foreach ($page_list as $item) {
			if ($item->parent_id != 0) {
				continue;
			}

			$return[ $item->id ] = array(
				'object' => $item,
				'childrens' => $this->_tree_childrens($item->id, $page_list, $childrens),
			);
		}

		return $return;
	}

	private function _tree_childrens($id, $page_list, $childrens)
	{
		$return = array();

		if (empty($childrens[ $id ])) {
			return $return;
		}

		foreach ($childrens[ $id ] as $child_id) 	{
			$item = $page_list[ $child_id ];
			$return[ $item->id ] = array(
				'object' => $item,
				'childrens' => $this->_tree_childrens($item->id, $page_list, $childrens),
			);
		}

		return $return;
	}

	private function _get_childrens($id, $relations, $self_include = TRUE)
	{
		$return = array();
		$proc_ids = array( $id );

		$stop = FALSE;
		while ( ! $stop) {
			$childrens = array();
			foreach ($proc_ids as $v) {
				if (in_array($v, $return)) {
					continue;
				}
				$childrens = array_merge( $childrens, array_keys($relations, $v) );
			}

			$proc_ids = $childrens;
			if (empty($proc_ids)) {
				$stop = TRUE;
			} else {
				$return = array_merge( $return, $childrens );
			}
		}

		if ($self_include) {
			$return[] = $id;
		}

		return $return;
	}

} 
