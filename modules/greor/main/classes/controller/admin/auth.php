<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Auth extends Controller_Template {

	protected $acl;
	protected $config = 'admin/site';

	public $template = 'auth';

	public function before()
	{
		Session::$default = 'admin';

		$template = $this->template;
		$this->template = NULL;
		
		parent::before();
		
		if ($this->auto_render === TRUE) {
			$this->template = View_Admin::factory($template);
		}

		$this->acl = A2::instance('admin/a2');
		$this->config = Kohana::$config->load($this->config)
			->as_array();
	}

	public function after()
	{
		View::set_global('ASSETS', $this->config['assets']);
		
		parent::after();

		$this->response
			->headers('cache-control', 'no-cache')
			->headers('expires', gmdate('D, d M Y H:i:s', time()).' GMT');
	}

	public function action_index()
	{
		$request = $this->request->current();
		if ($request->post('submit')) {
			$login = $request->post('login');
			$password = $request->post('password');
			$ip = Request::$client_ip;
			$user_agent = Request::$user_agent;
			$remember = (bool) $this->request->post('remember');

			$fail_login_checker = new Auth_Admin_Checker($login, $ip);

			if ($fail_login_checker->check()) {

				$admin = ORM::factory('admin')
					->where('username', '=', $login)
					->and_where('delete_bit', '=', 0)
					->and_where('active', '=', 1)
					->find();

				try {
					if ($this->acl->auth()->login($admin, $password, $remember)) {
						$url = Session::instance()->get('BACK_URL');
						$request->redirect(empty($url) ? Route::url('admin') : $url);
					} else {
						// Store fail login attempt
						$fail_login_checker->add($password, $user_agent);
						$this->template
							->set('error', __('Authentication error'));
					}
				} catch (ORM_Validation_Exception $e) {
					Log::instance()
						->add(Log::ERROR, $e->errors('').'['.__FILE__.'::'.__LINE__.']');
				}
			} else {
				$this->template
					->set('error', __('To many failed login attempts. Please, wait :minutes minutes and try again.', array(
						':minutes' => ceil($fail_login_checker->fail_interval() / 60)
					)
				));
			}
		}

		$this->template
			->set('logo', $this->config['logo']);
	}
} 
