<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Error extends Controller_Admin_Front {

	public $template = 'error';
	protected $title = 'Internal Server Error';

	public function before()
	{
		$request = $this->request->current();
		if ( ! $request->initial()->response()) {
			$request->initial()->response($request->response());
		}
		
		$this->ttl = 0;

		parent::before();

		$this->template->page = URL::site( rawurldecode($request->initial()->uri()) );
		$this->template->code = $request->action();
		$this->template->message = '';

		// Internal request only!
		if ($request->initial() !== Request::$current) {
			if ($message = rawurldecode($request->param('message'))) {
				$this->template->message = $message;
			}
		} else {
			$request->action(404);
		}

		$this->response->status((int) $request->action());
	}

	public function action_403()
	{
		$this->title = '403 Forbidden';
		$this->response->status(403);
	}

	public function action_404()
	{
		$this->title = '404 Not Found';
		$this->response->status(404);
	}

	public function action_500()
	{
		$this->title = '500 Internal Server Error';
		$this->response->status(500);
	}

	public function action_503()
	{
		$this->title = '503 Service Unavailable';
		$this->response->status(503);
	}

} 
