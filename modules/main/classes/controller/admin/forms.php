<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Forms extends Controller_Admin_Front {

	protected $menu_active_item = 'settings';
	protected $title = 'Forms';
	protected $sub_title = 'Forms';
	
	protected $owner;
	protected $owner_list;
	protected $not_deleted_owner = array();
	protected $controller_name = array(
		'structure' => 'forms_structure',
		'responses' => 'forms_responses',
	);
	
	public function before()
	{
		parent::before();
		
		$this->owner_list = $this->get_owner_list();
		$this->template
			->bind_global('OWNER_LIST', $this->owner_list);
		
			
		$this->owner = Request::current()->query('owner');
		if (empty($this->owner)) {
			$this->owner = (string) key($this->owner_list);
		}
		$this->template
			->bind_global('OWNER', $this->owner);
		
		
		$this->not_deleted_owner = Kohana::$config->load('forms.not_deleted_owner');
		$this->template
			->bind_global('NOT_DELETED_OWNER', $this->not_deleted_owner);
			
		$query_controller = $this->request->query('controller');
		if ( ! empty($query_controller) AND is_array($query_controller)) {
			$this->controller_name = $this->request->query('controller');
		}
		$this->template
			->bind_global('CONTROLLER_NAME', $this->controller_name);
		
		$this->title = __($this->title);
		$this->sub_title = __($this->sub_title);
	}
	
	protected function get_owner_list()
	{
		$list = Kohana::$config->load('forms.owner_list');
		$result = array();
		foreach ($list as $_k => $_v) {
			$result[$_k] = __($_v['title']);
		}
		return $result;
	}
	
	protected function layout_aside()
	{
		$menu_items = array_merge_recursive(
			Kohana::$config->load('admin/aside/forms')->as_array(),
			$this->menu_left_ext
		);
		
		return parent::layout_aside()
			->set('menu_items', $menu_items)
			->set('replace', array(
				'{OWNER}' => $this->owner,
			));
	}

	protected function left_menu_form_add()
	{
		$this->menu_left_add(array(
			'forms_structure' => array(
				'sub' => array(
					'add' => array(
						'title' => __('Add form'),
						'link' => Route::url('admin', array(
							'controller' => $this->controller_name['structure'],
							'action' => 'edit',
							'query' => 'owner={OWNER}'
						)),
					),
				),
			),
		));
	}
	
	protected function left_menu_csv_export($id)
	{
		$this->menu_left_add(array(
			'forms_responses' => array(
				'sub' => array(
					'export_csv' => array(
						'title' => __('Export CSV'),
						'link' => Route::url('admin', array(
							'controller' => $this->controller_name['responses'],
							'action' => 'csv',
							'id' => $id,
							'query' => 'owner={OWNER}'
						)),
					),
				),
			),
		));
	}
	
	protected function left_menu_messages_list($id)
	{
		$this->menu_left_add(array(
			'forms_responses' => array(
				'sub' => array(
					'messages_list' => array(
						'title' => __('Messages'),
						'link' => Route::url('admin', array(
							'controller' => $this->controller_name['responses'],
							'action' => 'form',
							'id' => $id,
							'query' => 'owner={OWNER}'
						)),
					),
				),
			),
		));
	}
}

