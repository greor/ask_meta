<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Module {

	/**
	 * Module types (see _modules config)
	 */
	const MODULE_SINGLE = 'single';
	const MODULE_MULTI = 'multi';
	const MODULE_STANDALONE = 'standalone';
	const MODULE_HIDDEN = 'hidden';

	protected static $_modules_config = '_modules';
	protected static $_modules = NULL;
	

	/**
	 * Check module exist
	 * @param satring $name
	 */
	public static function check_module($name)
	{
		return array_key_exists($name, Kohana::modules());
	}

	/**
	 * Return module list declared in _modules config
	 * If module type is Single and it co
	 */
	public static function modules()
	{
		if (self::$_modules === NULL) {
			self::$_modules = array();
			$_modules = Kohana::$config->load( self::$_modules_config );
	
			foreach ($_modules as $code => $config) {
				if ($config['type'] == self::MODULE_HIDDEN)
					continue;
				
				if ($config['type'] != self::MODULE_STANDALONE) {
					if ( ! self::check_module($config['alias']))
						continue;
				}
	
				self::$_modules[ $code ] = $config;
			}
		}

		return self::$_modules;
	}

	/**
	 * Return linked modules list
	 */
	public static function linked_modules()
	{
		return ORM::factory('page')
			->where('type', '=', 'module')
			->find_all()
			->as_array('id', 'data');
	}
	
	/**
	 * Check module for 'stanalone' type
	 */
	public static function is_stanalone($name)
	{
		if ( ! empty($name)) {
			$module = Arr::get(self::modules(), $name);
			if ( ! empty($module) AND $module['type'] == self::MODULE_STANDALONE)
				return TRUE;
		}
		
		return FALSE;
	}
	
	/**
	 * Return module code by controller name
	 */
	public static function code_by_controller($controller_name)
	{
		$_modules = Kohana::$config->load( self::$_modules_config );
		foreach ($_modules as $code => $config) {
			$_controller = empty($config['controller']) ? $code : $config['controller'];
			if ($_controller === $controller_name) {
				return $code;
			}
		}
		return FALSE;
	}

	/**
	 * Return module config
	 * 
	 * @param $config config name
	 */
	public static function load_config($config)
	{
		return Kohana::$config->load('admin/modules/'.$config)
			->as_array();
	}
};
