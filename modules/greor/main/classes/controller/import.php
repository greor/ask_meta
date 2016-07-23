<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Import extends Controller {
	
	protected $config;
	protected $target_db;
	protected $log_table = 'log_import';
	protected $log_table_errors = 'log_import_errors';
	
	public function before() {
		if (Kohana::$environment !== Kohana::DEVELOPMENT AND ! Kohana::$is_cli) {
			throw new Exception('Access error');
		}

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
			->execute($this->target_db);
	}
	
	protected function log_error($code, $key, $error)
	{
		DB::insert($this->log_table_errors, array('code', 'key', 'error'))
			->values(array($code, $key, $error))
			->execute($this->target_db);
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
				->execute($this->target_db)
				->as_array(NULL, 'key');
		}
		
		return $result;
	}
	
	protected function log_keys_errors($code, $sub_request = TRUE)
	{
		$builder = DB::select('key')
			->from($this->log_table_errors)
			->where('code', '=', $code);
			
		if ($sub_request) {
			$result = $builder;
		} else {
			$result = $builder
				->execute($this->target_db)
				->as_array(NULL, 'key');
		}
		
		return $result;
	}
	
	protected function load_to_tmp($src)
	{
		sleep(1);
		$dest_dir = str_replace('/', DIRECTORY_SEPARATOR, DOCROOT.'upload/tmp/');
		if ( ! is_dir($dest_dir)) {
			mkdir($dest_dir, 0755, TRUE);
		}
	
		$filename = uniqid();
		
		$fp = fopen($dest_dir.$filename.'.tmp', 'w+');
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, str_replace(" ", "%20", $src));
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
		
		curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		fclose($fp);
		
		if ($http_code == 200) {
			$ext = pathinfo($src, PATHINFO_EXTENSION);
			if (rename($dest_dir.$filename.'.tmp', $dest_dir.$filename.'.'.$ext)) {
				$result = $dest_dir.$filename.'.'.$ext;
			} else {
				$result = FALSE;
			}
		} else {
			unlink($dest_dir.$filename.'.tmp');
			$result = FALSE;
		}
		
		return $result;
	}
}