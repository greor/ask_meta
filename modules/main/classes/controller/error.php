<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Error extends Controller_Template {

	protected $config = 'site';
	protected $assets;

	public $template = 'error';

	public function before()
	{
		$this->ttl = 3600;

		$template = $this->template;
		$this->template = NULL;

		parent::before();

		if ($this->auto_render === TRUE) {
			$this->template = View_Theme::factory($template);
		}

		$this->config = Kohana::$config->load($this->config)
			->as_array();
		$this->assets = '/assets/'.$this->config['theme'].'/';

		$this->template->page = URL::site( rawurldecode(Request::$initial->uri()) );
		$this->template->code = $this->request->action();
		$this->template->message = '';

		// Internal request only!
		if (Request::$initial !== Request::$current) {
//			Скрываем, т.к. выводится лишняя информация
//			if ($message = rawurldecode( $this->request->param('message') ))
//			{
//				$this->template->message = $message;
//			}
		} else {
			$this->request->action(404);
		}

		if ($this->request->initial()->is_ajax() === TRUE) {
			$this->response->status((int) $this->request->action());
			$this->response->body(isset($message) ? $message : 'Page not found');
		}
	}

	public function after()
	{
		$referrer = Request::current()->referrer();

		if ($referrer === NULL OR ( $_SERVER['HTTP_HOST'] != parse_url($referrer, PHP_URL_HOST) )) {
			$referrer = URL::base();
		}

		$this->template
			->set('ASSETS', $this->assets)
			->set('BACK_URL', $referrer);
		
		parent::after();
	}

	public function action_404()
	{
		$this->response->status(404);
		$this->template->title = __('404 Page not found');
		$this->template->error_text = __('Page Not found');
	}

	public function action_503()
	{
		$this->response->status(503);
		$this->template->title = __('503 Maintenance Mode');
		$this->template->error_text = __('Maintenance Mode');
	}

	public function action_500()
	{
		$this->response->status(500);
		$this->template->title = __('500 Internal Server Error');
		$this->template->error_text = __('Internal Server Error');
	}
} 
