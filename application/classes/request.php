<?php defined('SYSPATH') or die('No direct script access.');

class Request extends Kohana_Request {

	public $page;
	public $site_code;
	public $site_id = 0;
	public $site_id_master = 0;

	public static function factory($uri = TRUE, HTTP_Cache $cache = NULL, $injected_routes = array())
	{
		$request = parent::factory($uri, $cache, $injected_routes);
		
		// for admin routes
		$exluded_routes = array('admin', 'admin_error', 'modules');
		if ( $request->route() !== NULL AND in_array($request->route()->route_name, $exluded_routes)) {
			return $request;
		}
		
		// for public routes
		$request->define_site();
		
		ORM_Base::$filter_mode = ORM_Base::FILTER_FRONTEND;
		ORM_Base::$site_id = $request->site_id;
		ORM_Base::$site_id_master = $request->site_id_master;
		if ($request->route() !== NULL) {
			return $request;
		}
		
		$request_uri = $request->uri();
		$request->page = $page = Page_Route::page( $request->uri() );

		if ( $page !== NULL ) {
			$routes = array();
			
			if ($page['type'] == 'module' AND ! Helper_Module::is_stanalone($page['data'])) {
				
				$routes_config = Kohana::$config->load('routes/'.$page['data'])->as_array();
				$request->set_module_routes($routes_config, $page['uri_full'], $page['id'], $cache);
				
			} elseif ( Helper_Module::is_stanalone($page['data']) ) {
				/*
				 * For controllers which no need admin side (only public contoller) 
				 * and have one action (by default is 'index')
				 * Can have route file or default controller (controller name equal 'data' field value) 
				 */
				$routes_config = Kohana::$config->load('routes/'.$page['data'])->as_array();
				
				if (empty($routes_config)) {
					$name = $page['id'].'<->standalone_page';
					$uri_callback = $page['uri_full'];
					$defaults = array(
						'directory'  => 'standalone',
						'controller' => $page['data'],
					);
					
					$route = new Route( $uri_callback );
					$route->defaults($defaults);
					$routes[ $name ] = $route;
	
					Route::set($name, $uri_callback)
						->defaults($defaults);
	
					$processed_uri = Request::process_uri($request_uri, $routes);
					if ($processed_uri !== NULL) {
						$request->set_dinamic_route( $processed_uri, $cache );
					}
					
				} else {
					$request->set_module_routes($routes_config, $page['uri_full'], $page['id'], $cache);
				}
				
			} else {
				
				/*
				 * For simple static pages
				 */
				$name = $page['id'].'<->std_page';
				$uri_callback = $page['uri_full'];
				$defaults = array(
					'controller' => 'page',
					'action'     => $page['type'],
				);
				
				$route = new Route( $uri_callback );
				$route->defaults($defaults);
				$routes[ $name ] = $route;

				Route::set($name, $uri_callback)
					->defaults($defaults);

				$processed_uri = Request::process_uri($request_uri, $routes);
				if ($processed_uri !== NULL) {
					$request->set_dinamic_route( $processed_uri, $cache );
				}
			}

		} else {
			Kohana::$log->add(Log::ERROR, 'Page for :uri not found. [:file][:line] ', array(
				':file' => Debug::path(__FILE__),
				':line' => __LINE__,
				':uri' => $request->uri()
			));

			throw new HTTP_Exception_404();
		}

		return $request;
	}

	public function set_module_routes($routes_config, $uri_base, $prefix, $cache)
	{
		$routes = array();

		foreach ($routes_config as $name => $route) {
			$name = $prefix.'<->'.$name;
			$uri_callback = $uri_base.Arr::get( $route, 'uri_callback' );
			$regex = Arr::get( $route, 'regex' );
			$defaults = Arr::get( $route, 'defaults' );

			$route = new Route( $uri_callback, $regex, $name );
			$route->defaults($defaults);
			$routes[ $name ] = $route;

			Route::set($name, $uri_callback, $regex)
				->defaults($defaults);
		}

		$processed_uri = Request::process_uri($this->uri(), $routes);
		if ($processed_uri !== NULL) {
			$this->set_dinamic_route( $processed_uri, $cache );
		}
	}

	public function set_dinamic_route( $processed_uri, $cache )
	{
		// Store the matching route
		$this->_route = $processed_uri['route'];
		$params = $processed_uri['params'];

		// Is this route external?
		$this->_external = $this->_route->is_external();

		if (isset($params['directory'])) {
			// Controllers are in a sub-directory
			$this->_directory = $params['directory'];
		}

		// Store the controller
		$this->_controller = $params['controller'];

		if (isset($params['action'])) {
			// Store the action
			$this->_action = $params['action'];
		} else {
			// Use the default action
			$this->_action = Route::$default_action;
		}

		// These are accessible as public vars and can be overloaded
		unset($params['controller'], $params['action'], $params['directory']);

		// Params cannot be changed once matched
		$this->_params = $params;

		// Apply the client
		$this->_client = new Request_Client_Internal(array('cache' => $cache));
	}

	protected function define_site()
	{
		$db_result = DB::select('id', 'code')
			->from('sites')
			->where('type', '=', 'master')
			->and_where('active', '>', 0)
			->and_where('delete_bit', '=', 0)
			->execute();
		$master_site_db = $db_result->current();
		$this->site_id_master = (int) $master_site_db['id'];
		
		if (Kohana::$is_cli) {
			$this->site_id = $this->site_id_master;
			return;
		}
		
		$this->site_code = Arr::get($this->_get, 'region');
		if (empty($this->site_code)) {
			$this->site_code = Arr::get($_COOKIE, 'region', '');
		}
		
		if ( ! empty($this->site_code)) {
			$db_result = DB::select('id', 'code')
				->from('sites')
				->where('code', '=', $this->site_code)
				->and_where('active', '>', 0)
				->and_where('delete_bit', '=', 0)
				->execute();
				
			/* Redirect to master site */
			if ($db_result->count() <= 0) {
				$this->redirect('/');
			}
			
			$site_db = $db_result->current();
			$this->site_id = (int) $site_db['id'];
		} else {
			$this->site_id = $this->site_id_master;
		}
	}

	public function set_param($name, $value)
	{
		$this->_params[$name] = $value;
	}
}