<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Like extends Controller_Front_Base {

	public $auto_render = FALSE;
	
	/*
	 * Caps for methods
	 */
	protected function init() {}
	public function after() {}
	
	public function action_index()
	{
		$request = $this->request->current();
		$config = Kohana::$config->load('like');
		$model = $request->param('model');
		$model_table = ORM::factory($model)->table_name();
		$permissions = $config->get('model', array());
		$response = array();
		
		if ( ! $request->is_ajax() OR ! in_array($model, $permissions)) {
			throw new HTTP_Exception_404;
		}
		
		$id = (int) $request->post('id');
		$vote = ((int) $request->post('vote') > 0) ? 1 : -1;
		
		$session = Session::instance();
		$session_key = 'like:'.$model;
		$session_data = $session->get($session_key, array());
		$session_vote = (int) Arr::get($session_data, $id);
		
		
		if ($session_vote != $vote) {
			$check = DB::select('id')
				->from($model_table)
				->where('id', '=', $id)
				->execute();
			
			if ( ! $check->count()) {
				throw new HTTP_Exception_404;
			}
			
			$orm = ORM::factory('like')
				->where('model', '=', $model)
				->and_where('element_id', '=', $id)
				->find();
			
			if ( ! $orm->loaded()) {
				$orm->values(array(
					'model' => $model,
					'element_id' => $id,
					'count' => 0,
				))->save();
			}
			
			$like_expires = DB::select('id', 'like_id', 'limit', 'ip', 'user_agent', 'expires')
				->from($orm->expires_table)
				->where('like_id', '=', $orm->id)
				->and_where('expires', '>', time())
				->and_where('ip', '=', Request::$client_ip)
				->and_where('user_agent', '=', Request::$user_agent)
				->execute();
			
			try {
				if ($like_expires->count() > 0) {
					$_data = $like_expires->as_array();
					$_data = reset($_data);
					
					if ($_data['limit'] < $config->get('max_like_count')) {
						
						DB::update($orm->expires_table)
							->set(array(
								'limit' => ++$_data['limit']
							))->execute();
						
						$response['success'] = TRUE;
					} else {
						$response['success'] = FALSE;
					}
				} else {
					DB::insert($orm->expires_table, array(
						'like_id', 'ip', 'user_agent', 'expires'
					))->values(array(
						$orm->id, Request::$client_ip, Request::$user_agent, (time() + $config->get('expires'))
					))->execute();
					
					$response['success'] = TRUE;
				}
				
				if ($response['success']) {
					$orm->count += $vote;
					$orm->save();
					
					$response['message'] = __('You have successfully voted.');
					$session_data[$id] = $vote;
				} else {
					$response['error'] = __('High activity from your ip. Try vote later.');
					$response['code'] = 'hight_activity';
				}
			} catch (Exception $e) {
				Kohana::$log->add(Log::ERROR, 'File: :file. Exception occurred: :exception', array(
					':file' => __FILE__.':'.__LINE__,
					':exception' => $e->getMessage()
				));
			}
		} else {
			$response['success'] = FALSE;
			$response['code'] = 'already_voted';
			$response['error'] = __('You have already voted!');
		}

		$session->set($session_key, $session_data);
		
		$this->json_send($response);
	}
} 