<?php defined('SYSPATH') OR die('No direct access allowed.');

class HTML_Optimizator {
	
	public static $instance = null;
	private $_body = null;
	private $_buffer = array();
	
	public static function instance($html)
	{
		if (empty(self::$instance)) {
			self::$instance = new HTML_Optimizator($html);
		}
		return self::$instance;
	}
	
	public function __construct($html)
	{
		$this->_body = $html;
	}
	
	public function run($from, $to, $insert_to)
	{
		$start = strpos($this->_body, $from);
		$end = strpos($this->_body, $to);
		$length = $end-$start+strlen($to);
		
		$_content = substr($this->_body, $start, $length);
		
		$this->_body = substr_replace(
			$this->_body,
			preg_replace_callback(
				'#(?:<script|<script[\s]+type="text/javascript")[\s]*>(.*?)</script>#is', 
				array($this, '_remove_scripts'),
				$_content
			),
			$start,
			$length
		);
		
		return str_replace($insert_to, '<script>'.implode('', $this->_buffer).'</script>', $this->_body);
	}
	
	private function _remove_scripts($match)
	{
		$this->_buffer[] = $match[1];
	}
	
}