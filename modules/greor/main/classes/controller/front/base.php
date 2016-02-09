<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Front_Base extends Controller_Template {

	/* Page templates */
	protected $layout = 'layout/template';
	public $template = 'empty';

	/* Template vars */
	protected $title;
	protected $head_tags = array();
	protected $title_delimiter = ' - ';
	
	protected $assets;
	protected $no_img = 'misc/no_img.jpg';
	protected $null_img = 'misc/0.gif';

	/* Internal vars */
	private $_site;
	protected $config = 'site';
	protected $page_id;
	protected $menu_cache_key = 'menu';
	protected $menu_handlers = array();
	protected $breadcrumbs = array();
	protected $json = array();

	/* Cache settings */
	protected $ttl = 60;
	protected $auto_send_cache_headers = TRUE;

	
	protected function init()
	{
		$this->config = Kohana::$config->load($this->config)
			->as_array();
		
		$this->assets = '/assets/'.$this->config['theme'].'/';
		$this->no_img = ltrim($this->assets.$this->no_img, '/');
		$this->null_img = $this->assets.$this->null_img;

		if ($this->request->page !== NULL) {
			$this->page_id = $this->request->page['id'];
		}
		$this->site_init();
		
		$this->breadcrumbs = $this->breadcrumbs();
		$this->meta_seo_init();
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
	}
	
	protected function site_properties($site_id)
	{
		return ORM_Helper::factory('site', $site_id)
			->property_list();
	}
	
	protected function site(array $value = NULL)
	{
		if ($value === NULL) {
			return $this->_site;
		} else {
			$value['properties'] = $this->site_properties($value['id']);
			$this->_site = $value;
		}
	}

	protected function breadcrumbs($page_id = NULL)
	{
		$page_id = empty($page_id) ? $this->page_id : $page_id;
		
		$return = array();
		$url_base = URL::base();
	
		if ( ! empty($page_id)) {
			$current_page_id = $page_id;
			$stop = FALSE;
	
			while ( ! $stop) {
				$_tmp = Page_Route::page_by_id($current_page_id);
	
				$return[] = array(
					'title' => $_tmp['title'],
					'link' => $url_base.$_tmp['url']
				);
	
				$current_page_id = $_tmp['parent_id'];
				if ( $current_page_id == 0 ) {
					$stop = TRUE;
				}
			}
		}
	
		$return[] = array(
			'title' => __('Home page'),
			'link' => $url_base
		);
	
		return array_reverse($return);
	}
	
	public function before()
	{
		if ($this->auto_render === TRUE) {
			$template = $this->template;
			$this->template = NULL;
			parent::before();
			$this->template = View_Theme::factory($template);
		} else {
			parent::before();
		}
	
		$this->init();
	}
	
	protected function meta_seo_init()
	{
		$this->head_tags['keywords'] = array(
			'tag' => 'meta',
			'attr' => array(
				'class' => 'ajax-head',
				'name' => 'keywords',
				'content' => '',
			),
		);
		$this->head_tags['description'] = array(
			'tag' => 'meta',
			'attr' => array(
				'class' => 'ajax-head',
				'name' => 'description',
				'content' => '',
			),
		);
	}
			
	protected function meta_seo_load($model_name, $id)
	{
		if ($id) {
			return ORM::factory($model_name, $id)
				->as_array();
		}
		return array();
	}
	
	protected function meta_seo_extract(array $values)
	{
		$meta = Arr::extract($values, array('title_tag', 'keywords_tag', 'description_tag'), '');
		return array_filter($meta, 'strlen');
	}
	
	protected function meta_seo_get()
	{
		$page = $this->meta_seo_load('page', $this->page_id);
		return $this->meta_seo_extract($page);
	}
	
	protected function meta_seo_set(array $meta = array())
	{
		if ( ! empty($meta['keywords_tag'])) {
			$this->head_tags['keywords'] = array(
				'tag' => 'meta',
				'attr' => array(
					'class' => 'ajax-head',
					'name' => 'keywords',
					'content' => $meta['keywords_tag'],
				),
			);
		}
		if ( ! empty($meta['description_tag'])) {
			$this->head_tags['description'] = array(
				'tag' => 'meta',
				'attr' => array(
					'class' => 'ajax-head',
					'name' => 'description',
					'content' => $meta['description_tag'],
				),
			);
		}
	}
	
	protected function generate_title(array $meta_seo = array())
	{
		if ( ! empty($meta_seo['title_tag'])) {
			$this->title = $meta_seo['title_tag'];
		} else {
			$_title = Arr::get($this->meta_seo_extract($this->_site), 'title_tag', '');
			if (empty($this->title)) {
				$this->title = $_title;
			} elseif ( ! empty($_title)) {
				$this->title .= $this->title_delimiter.$_title;
			}
		}
	}
	
	protected function set_cache_headers()
	{
		if ($this->auto_send_cache_headers) {
			if ( ! DONT_USE_CACHE AND $this->ttl) {
				$this->response
					->headers('cache-control', 'public, max-age='.$this->ttl)
					->headers('expires', gmdate('D, d M Y H:i:s', time()+$this->ttl).' GMT');
			} else {
				$this->response
					->headers('cache-control', 'max-age=0, must-revalidate, public')
					->headers('expires', gmdate('D, d M Y H:i:s', time()).' GMT');
			}
		}
	}
	
	protected function template_set_global_vars()
	{
		if ($this->request->current()->is_initial()) {
			View::set_global('SITE', $this->site());
			View::set_global('TITLE', $this->title);
			View::set_global('HEAD_TAGS', $this->head_tags);
			View::set_global('ASSETS', $this->assets);
			View::set_global('CONFIG', $this->config);
			View::set_global('NO_IMG', $this->no_img );
			View::set_global('NULL_IMG', $this->null_img );
			View::set_global('PAGE_ID', $this->page_id );
			View::set_global('BREADCRUMBS', $this->breadcrumbs);
		}
	}
	
	public function after()
	{
		$meta_seo = $this->meta_seo_get();
		$site_meta = $this->meta_seo_extract($this->_site);
		
		$this->meta_seo_set($meta_seo + $site_meta);
		$this->generate_title($meta_seo);
		$this->template_set_global_vars();

		$this->set_cache_headers();
		
		$request = $this->request->current();
		if ($request->is_ajax() AND empty($this->json)) {
			$this->json['title'] = $this->title;
			$this->json['head_tags'] = $this->head_tags;
			$this->json['content'] = trim($this->layout_content());
		}
		
		if ( ! empty($this->json)) {
			$this->auto_render = FALSE;
			
			parent::after();
			
			$this->json_send($this->json);
		} else {
			if ($this->auto_render) {
				$this->layout_render();
			}
	
			parent::after();
		}
	}

	protected function layout_render()
	{
		$this->template = View_Theme::factory($this->layout, $this->layout_data());
	}
	
	protected function layout_content()
	{
		return $this->template
			->render();
	}
	
	protected function layout_data()
	{
		$this->menu_init_handlers();
		
		return array(
			'content' => $this->layout_content(),
			'menu' => $this->menu_get(),
		);
	}

	protected function menu_get()
	{
		$menu = NULL;
		if ( ! DONT_USE_CACHE) {
			try {
				$menu = Cache::instance('struct')
					->get($this->menu_cache_key);
			} catch (ErrorException $e) {};
		}

		if($menu === NULL) {
			$statuses = Kohana::$config
				->load('_pages.status_codes');

			$pages = ORM::factory('page')
				->where('status', '=', $statuses['active'])
				->and_where('level', '<', 3)
				->order_by('level', 'asc')
				->order_by('position', 'asc')
				->find_all();

			$menu = $this->menu_parse_item($pages, URL::base());

			if ( ! DONT_USE_CACHE) {
				try {
					Cache::instance('struct')
						->set($this->menu_cache_key, $menu, Date::HOUR);
				} catch (ErrorException $e) {};
			}
		}

		return $menu;
	}

	protected function menu_parse_item($pages, $url)
	{
		$return = array();
		
		$aliases = Kohana::$config->load('pages_aliases')
			->as_array();
		$aliases = array_filter($aliases);
		$aliases = array_flip($aliases);
			
		foreach ($pages as $_orm) {
			$_alias = Arr::get($aliases, $_orm->name);
			$_item = array();
			$_item['name'] = $_orm->name;
			$_item['alias'] = $_alias;
			$_item['title'] = $_orm->title;
			$_item['target'] = '_self';
			if ( $_orm->type == 'url' AND strpos($_orm->data, '//') !== FALSE) {
				$_item['target'] = '_blank';
			}
			$_item['sub'] = array();
			
			if ( $_orm->type == 'url' ) {
				$_item['uri'] = $_orm->data;
				$_owner = & $return;
			} elseif (isset($return[ $_orm->parent_id ])) {
				// добавляем к родителю и генерим ури
				$_item['uri'] = $return[ $_orm->parent_id ]['uri'].'/'.$_orm->uri;
				$_owner = & $return[ $_orm->parent_id ]['sub'];
			} else {
				$_item['uri'] = $_orm->uri;
				$_owner = & $return;
			}
			
			// проверяем наличие индивидуального обработчика
			if ( ! empty($_alias) AND ! empty($this->menu_handlers[$_alias])) {
				foreach ($this->menu_handlers[$_alias] as $_func) {
					call_user_func_array($_func, array( & $_item));
				}
			}
			// проверяем наличие общего обработчика
			if ( ! empty($this->menu_handlers[TRUE])) {
				foreach ($this->menu_handlers[TRUE] as $_func) {
					call_user_func_array($_func, array( & $_item));
				}
			}
			
			$_owner[ $_orm->id ] = $_item;
		}
		
		return $return;
	}
	
	protected function menu_init_handlers() {}

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
			
			if ($request->method() !== Request::GET) {
				$response->headers('Cache-Control', 'no-store, no-cache, must-revalidate');
				$response->headers('Pragma', 'no-cache');
			}
		} elseif ($ttl) {
			$this->response
				->headers('cache-control', 'public, max-age='.$ttl)
				->headers('expires', gmdate('D, d M Y H:i:s', time()+$ttl).' GMT');
		}
	}
} 