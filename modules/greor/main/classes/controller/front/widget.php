<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Front_Widget extends Controller_Front_Multisite {
	
	protected function init()
	{
		$this->config = Kohana::$config->load($this->config)
			->as_array();
	
		$this->assets = '/assets/'.$this->config['theme'].'/';
		$this->no_img = ltrim($this->assets.$this->no_img, '/');
		$this->null_img = $this->assets.$this->null_img;
	
		$this->site_init();
	}	
	
	protected function redirect($link = NULL, $code = 302) {
		if (empty($link)) {
			$link = URL::base();
		}
		
		$this->request->current()
			->redirect($link, $code);
	}
	
	public function before()
	{
		parent::before();
		
		$requset = $this->request->current();
		if ($requset->is_initial() AND ! $requset->is_ajax()) {
			$this->redirect();
		}
	}
	
	public function after()
	{
		$this->template_set_global_vars();
		
		$request = $this->request->current();
		if ($request->is_ajax() AND empty($this->json)) {
			$this->json['content'] = trim($this->template->render());
		}
		
		if ( ! empty($this->json)) {
			$this->json_send($this->json);
		} elseif ($this->auto_render) {
			$this->response->body($this->template->render());
		}
	}
}