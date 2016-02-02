<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Front_Base extends Controller_Template {

	/* Page templates */
	public $template = 'empty';
	
	/* Internal vars */
	private $_site;
	protected $acl;
	protected $user;
	protected $config = 'admin/site';
	protected $is_cancel;
	
	/**
	 * URL from query ($_GET['back_url']) to redirect after action (e.g. edit, or delete).
	 * Rewrite standart (definded in controller) URL to redirect.
	 * Used for actions with redirecting.
	 *
	 * Practicaly used when injecting sub-requested element list in edit form
	 * (tab main, tab description, tab injected list).
	 *
	 * @var string
	 */
	protected $back_url;

	/**
	 * Array of class-injectors. List of: <key> => array(<class_name>, <param_list>)
	 * @var array
	 */
	protected $injectors = array();
	
	/* Menu */
	protected $menu_active_item;
	protected $menu_left_ext = array();
	
	/* Modules */
	protected $module_config;
	protected $module_page_id;
	protected $module_pages = array();
	
	/* Page templates */
	public $layout = 'layout/template';
	public $layout_aside = 'layout/aside';
	
	/* Template vars */
	protected $title;
	protected $sub_title;
	protected $hook_list_content = array();
	protected $breadcrumbs = array();
	
	/* Cache settings */
	protected $auto_send_cache_headers = TRUE;
	
	protected function init()
	{
		$request = $this->request->current();
		$this->config = Kohana::$config->load($this->config)
			->as_array();
		
		$this->acl_init();
		$this->site_init();
		
		if (Route::name($request->route()) == 'modules') {
			$this->module_page_id = (int) $request->query('page');
			
			$this->module_config = empty($this->module_config)
				? Helper_Module::code_by_controller($request->controller())
				: $this->module_config;
				
			$_pages = $this->get_module_pages($this->module_config);
			if ($_pages->count() > 0) {
				if ($this->module_page_id == 0) {
					$this->module_page_id = $_pages->rewind()->current()->id;
				}
				foreach ($_pages as $_item) {
					$_link = URL::base().Page_Route::dynamic_base_uri($_item->id);
					$this->module_pages[ $_item->id ] = $_item->title." [ {$_link} ]";
				}
			}
			
			$this->module_config = Helper_Module::load_config($this->module_config);
			$this->acl_module();
			
			if ( ! $this->acl->is_allowed($this->user, $request->controller().'_controller', 'access')) {
				throw new HTTP_Exception_404();
			}
		}
		
		$injectors = array();
		foreach ($this->injectors as $_key => $_array) {
			$params = Arr::get($_array, 1);
			$object = new $_array[0]($request, $this->user, $this->acl, $params);
			$injectors[$_key] = $object;
		}
		$this->injectors = $injectors;
		unset($injectors);
		
		$this->is_cancel = ($request->post('cancel') == 'cancel');
		$this->back_url = $request->query('back_url');
		$this->post_check_empty_files();
		$this->post_check_deleted_fields();
	}
	
	protected function site_init()
	{
		$site = ORM::factory('site')
			->find()
			->as_array();
		$this->site($site);
		
		if ($this->request->is_initial()) {
			define('SITE_ID', $site['id']);
		}
		
		ORM_Base::$site_id = SITE_ID;
	}
	
	protected function site(array $value = NULL)
	{
		if ($value === NULL) {
			return $this->_site;
		} else {
			$this->_site = $value;
		}
	}
	
	protected function acl_init()
	{
		$request = $this->request->current();
		
		if ( ! Kohana::$is_cli) {
			$this->acl = A2::instance('admin/a2');
			if ( ! $this->acl->logged_in() AND $request->controller() != 'auth') {
				Session::instance()
					->set('BACK_URL', $_SERVER['REQUEST_URI']);
				
				$request->redirect(Route::url('admin', array(
					'controller' => 'auth'
				)));
			}
			$this->user = $this->acl->get_user();
			
			if ($request->is_initial()) {
				define('IS_SUPER_USER', ($this->user->role == 'super'));
			}
		}
	}
	
	protected function acl_roles($all = FALSE)
	{
		$roles = Kohana::$config->load('admin/a2.roles');
		if ( ! $all) {
			unset($roles['user']);
			unset($roles['super']);
		}
		$roles = array_keys($roles);
	
		return array_combine($roles, $roles);
	}
	
	private function acl_module()
	{
		if (Kohana::$is_cli) {
			return;
		}
	
		$config = Arr::get($this->module_config, 'a2');
		$helper_acl = new Helper_ACL($this->acl);
		$helper_acl->inject($config);
	}
	
	private function get_module_pages($module_key)
	{
		return ORM::factory('page')
			->where('status', '>', 0)
			->and_where('type', '=', 'module')
			->and_where('data', '=', $module_key)
			->find_all();
	}
	
	
	private function post_check_empty_files()
	{
		if ( ! empty($_FILES)) {
			$new_files = array();
			foreach ($_FILES as $field => $value) {
				if ($value['error'] === UPLOAD_ERR_NO_FILE) {
					continue;
				}
				$new_files[$field] = $value;
			}
			$_FILES = $new_files;
		}
	}
	
	private function post_check_deleted_fields()
	{
		$delete_fields = $this->request->current()
			->post('delete_fields');
		
		if ( ! empty($delete_fields)) {
			foreach ($delete_fields as $field => $v) {
				if ( (bool) $v) {
					$_FILES[ $field ] = '';
				}
			}
		}
	}
	
	public function before()
	{
		Session::$default = 'admin';
		ORM_Base::$filter_mode = ORM_Base::FILTER_BACKEND;
	
		if ($this->auto_render === TRUE) {
			$template = $this->template;
			$this->template = NULL;
			parent::before();
			$this->template = View_Admin::factory($template);
		} else {
			parent::before();
		}
		
		$this->init();
	}
	
	protected function set_cache_headers()
	{
		if ($this->auto_send_cache_headers) {
			$this->response
				->headers('cache-control', 'no-cache')
				->headers('expires', gmdate('D, d M Y H:i:s', time()).' GMT');
		}
	}
	
	protected function template_set_global_vars()
	{
		View::set_global('SITE', $this->site());
		View::set_global('CONFIG', $this->config);
		View::set_global('ACL', $this->acl);
		View::set_global('USER', $this->user);
		
		View::set_global('MODULE_PAGE_ID', $this->module_page_id);
		View::set_global('MODULE_PAGES', $this->module_pages);
		
		View::set_global('TITLE', $this->title);
		View::set_global('ASSETS', $this->config['assets']);
		View::set_global('BREADCRUMBS', $this->breadcrumbs);
		
		View::set_global('BACK_URL', $this->back_url);
	}
	
	public function after()
	{
		$this->template_set_global_vars();
		if ($this->auto_render) {
			$this->layout_render();
		}
		
		parent::after();
		
		$this->set_cache_headers();
	}
	
	protected function menu_get()
	{
		$return = array();
		if (empty($this->menu_active_item)) {
			$this->menu_active_item = Request::current()->controller();
		}
	
		$menu = Kohana::$config->load('admin/menu')
			->as_array();
		foreach ($menu as $_title => $_controller) {
			if ($_controller !== 'logout' AND ! $this->acl->is_allowed($this->user, $_controller, 'read')) {
				continue;
			}
			
			$return[] = array(
				'title' => $_title,
				'uri' => Route::url('admin', array('controller' => $_controller)),
				'class' => ($this->menu_active_item == $_controller) ? 'active' : '',
			);
		}
	
		return $return;
	}
	
	protected function menu_left_add($menu)
	{
		if (empty($menu)) {
			return;
		}
		$this->menu_left_ext = array_merge_recursive($this->menu_left_ext, $menu);
	}
	
	protected function layout_aside()
	{
		return $this->layout_aside ? View_Admin::factory($this->layout_aside) : '';
	}
	
	protected function layout_render()
	{
		if ( ! $this->request->current()->is_ajax()) {
			$this->template
				->set('breadcrumbs', $this->_get_breadcrumbs());
		}
		
		$content = $this->template
			->render();
		
		if ( ! empty($this->hook_list_content)) {
			foreach ($this->hook_list_content as $_hook) {
				if (is_array($_hook[0])) {
					$reflectionMethod = new ReflectionMethod($_hook[0][0], $_hook[0][1]);
					$obj = $_hook[0][0];
				} elseif (is_string($_hook[0])) {
					$reflectionMethod = new ReflectionMethod($_hook[0]);
					$obj = NULL;
				}
				$_args = array($content);
				if ( ! empty($_hook[1]) AND is_array($_hook[1])) {
					$_args = array_merge($_args, $_hook[1]);
				}
				$content = $reflectionMethod->invokeArgs($obj, $_args);
			}
		}
				
		/*
		 * Если в query установлена 'content_only',
		 * тогда отдаем только контентную часть
		 */
		if ($this->request->current()->query('content_only')) {
			$this->template
				->set_filename('layout/content_only')
				->set('content', $content);
		} else {
			$title = HTML::chars(__($this->title));
			if ( ! empty($this->sub_title)) {
				$title .= '&nbsp;<small>'.HTML::chars(__($this->sub_title)).'</small>';
			}
			
			$this->template
				->set_filename('layout/template')
				->set('menu', $this->menu_get())
				->set('title', $title)
				->set('aside', $this->layout_aside())
				->set('content', $content);
		}
	}
	
	protected function meta_seo_reset($values, $reset_key)
	{
		$meta_columns = array( 
			'title_tag',
			'keywords_tag',
			'description_tag',
		);
	
		if ( ! $values[$reset_key]) {
			foreach ($meta_columns as $column) {
				if ( ! isset($values[$column])) {
					continue;
				}
				$values[$column] = '';
			}
		}
	
		return $values;
	}	
	

	protected function errors_extract(ORM_Validation_Exception $e)
	{
		$errors = $e->errors('');
		if ( ! empty($errors['_files'])) {
			$errors = array_merge($errors, $errors['_files']);
			unset($errors['_files']);
		}
		if ( ! empty($errors['_external'])) {
			$errors = array_merge($errors, $errors['_external']);
			unset($errors['_external']);
		}

		return $errors;
	}
	
	protected function element_delete($orm_helper, $where = NULL)
	{
		$return = TRUE;
		if ( ! empty($where)) {
			$orm = $orm_helper->orm()
				->reset();
			foreach ($where as $condition) {
				$orm->where($condition[0], $condition[1], $condition[2]);
			}
			$items = $orm->find_all();
			foreach ($items as $_orm) {
				$orm_helper->orm($_orm);
				$return = $return AND $this->element_delete($orm_helper);
			}
		} else {
			$errors = array();
			try {
				$old_deleter_id = $orm_helper->orm()->deleter_id;
				$orm_helper
					->save(array('deleter_id' => $this->user->id, 'deleted' => date('Y-m-d H:i:s')))
					->delete(FALSE, $where);
			} catch (ORM_Validation_Exception $e) {
				$errors = $this->errors_extract($e);
					
				try {
					$orm_helper->save(array( 
						'deleter_id' => $old_deleter_id,
					));
				} catch (ORM_Validation_Exception $e1) {
					$errors = array_merge($errors, $this->errors_extract($e1));
				}
					
				$this->template
					->set_filename('layout/error')
					->set('errors', $errors)
					->set('title', __('Error'));
			}
			$return = empty($errors);
		}
		return $return;
	}
	
	protected function element_position($orm_helper, $id, $mode)
	{
		$orm = $orm_helper->orm();
		if ($mode !== 'fix') {
			$orm
				->and_where('id', '=', $id)
				->find();
		
			if ( ! $orm->loaded() OR ! $this->acl->is_allowed($this->user, $orm, 'edit')) {
				throw new HTTP_Exception_404();
			}
		
			switch ($mode) {
				case 'up':
					$orm_helper
						->position_move('position', ORM_Position::MOVE_PREV);
					break;
				case 'down':
					$orm_helper
						->position_move('position', ORM_Position::MOVE_NEXT);
					break;
				case 'first':
					$orm_helper
						->position_first('position');
					break;
				case 'last':
					$orm_helper
						->position_last('position');
					break;
			}
		} else {
			if ($orm_helper->position_type() === ORM_Helper::POSITION_COMPLEX) {
				if ($this->acl->is_allowed($this->user, $orm, 'fix_all')) {
					$orm_helper
						->position_fix('position', ORM_Helper::POSITION_ALL);
				} elseif ($this->acl->is_allowed($this->user, $orm, 'fix_master')) {
					$orm_helper
						->position_fix('position', ORM_Helper::POSITION_MASTER);
				} elseif ($this->acl->is_allowed($this->user, $orm, 'fix_slave')) {
					$orm_helper
						->position_fix('position', ORM_Helper::POSITION_SLAVE);
				}
			} else {
				if ($this->acl->is_allowed($this->user, $orm, 'fix_all')) {
					$orm_helper
						->position_fix('position');
				}
			}
		}
	}

	/**
	 * Parse $values array for $field multiple date and return formated string
	 * 
	 * @param array $values
	 * @param string $field
	 * @return string
	 */
	protected function value_multiple_date(array $values, $field)
	{
		$date = Arr::get($values, $field);
		$return = '';
		if ( ! empty($date)) {
			$return = trim($date['date'].' '.$date['time']);
		}
		
		return $return;
	}
	
	protected function json_send($data = array(), $ttl = NULL)
	{
		$request = $this->request->current();
		$response = $request->response();
		$response
			->body(json_encode($data));
	
		if ( ! headers_sent()) {
			$mime = Kohana::$config->load('mimes.json');
			// Remove any existing headers from response
			$response->headers(array());
				
			$response->headers('Last-Modified', gmdate('D, d M Y H:i:s').' GMT');
			$response->headers('Content-Transfer-Encoding', '8bit');
			$response->headers('Content-Type', $mime[0].'; charset='.Kohana::$charset);
			$response->headers('Content-Length', strlen($response->body()));
				
			if ( ! $this->auto_send_cache_headers AND $ttl) {
				$this->response
					->headers('Cache-Control', 'public, max-age='.$ttl)
					->headers('Expires', gmdate('D, d M Y H:i:s', time()+$ttl).' GMT');
			} else {
				$this->response
					->headers('Expires', ' Mon, 26 Jul 1997 05:00:00 GMT')
					->headers('Cache-Control', 'no-store, no-cache, must-revalidate')
					->headers('Pragma', 'no-cache');
			}
		}
	}
	
	protected function _get_breadcrumbs()
	{
		return $this->breadcrumbs;
	}
	
	protected function load_to_tmp($src)
	{
		sleep(1);
		$dest_dir = str_replace('/', DIRECTORY_SEPARATOR, DOCROOT.'upload/tmp/');
		if ( ! is_dir($dest_dir)) {
			mkdir($dest_dir, 0755, TRUE);
		}
	
		if (FALSE !== $data = file_get_contents($src)) {
			$dest_file = $dest_dir.UTF8::strtolower( uniqid().'_'.basename($src));
			if (FALSE !== file_put_contents($dest_file, $data)) {
				return $dest_file;
			}
		}
	
		return FALSE;
	}
	
} 