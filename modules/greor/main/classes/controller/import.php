<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Import extends Controller {
	
	protected $config;
	protected $target_db;
	protected $log_table = 'log_import';
	
	public function before() {
		$this->config = Kohana::$config->load('import')
			->as_array();
		$this->target_db = Database::instance('import', Arr::get($this->config, 'database'));
	}
	
	public function after() {}
	
	protected function show_last_query()
	{
		echo Database::instance('import')->last_query; 
		die;
	}
	
	protected function log_add($code, $key)
	{
		DB::insert($this->log_table, array('code', 'key'))
			->values(array($code, $key))
			->execute();
	}
	
	protected function log_keys($code, $sub_request = TRUE)
	{
		$builder = DB::select('key')
			->from($this->log_table)
			->where('code', '=', $code);
			
		if ($sub_request) {
			$result = $builder;
		} else {
			$result = $builder
				->execute()
				->as_array(NULL, 'key');
		}
		
		return $result;
	}
}