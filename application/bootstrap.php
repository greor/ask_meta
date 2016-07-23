<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/kohana/core'.EXT;

if (is_file(APPPATH.'classes/kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Europe/Moscow');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'ru_RU.utf-8');
setlocale(LC_NUMERIC, 'C');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('ru-ru');

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV'])) {
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
} elseif (defined('DEVELOPER') AND DEVELOPER === TRUE) {
	Kohana::$environment = Kohana::DEVELOPMENT;
} else {
	Kohana::$environment = Kohana::PRODUCTION;
}

// Load user extensions
if (is_file(APPPATH.'vendor/init'.EXT)) {
	require APPPATH.'vendor/init'.EXT;
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
	'profile'    => (Kohana::$environment !== Kohana::PRODUCTION),
	'caching'    => (Kohana::$environment === Kohana::PRODUCTION),
	'cache_life' => 3600*24
));

define('DONT_USE_CACHE', (Kohana::$environment !== Kohana::PRODUCTION));
define('FTP_UPLOAD', dirname(DOCROOT).DIRECTORY_SEPARATOR.'ftp_upload');

Cookie::$salt = 'btmem5sgcy4ydcu0j0fsjalo97jx2aqm7wtrh4kf5v';
Route::$preview_salt = '10c4323c4f563c54a149ddbfdfc5c8eb'.date('y-m-d F l');

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	'auth'       => MODPATH.'auth',       // Basic authentication
	'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'   => MODPATH.'database',   // Database access
	'image'      => MODPATH.'image',      // Image manipulation
	'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	
	'captcha' => MODPATH.'captcha', // Captcha module 3.2
	'acl' => MODPATH.'wouterrr/acl', // ACL module
	'a1' =>	MODPATH.'wouterrr/a1', // A1 module
	'a2' =>	MODPATH.'wouterrr/a2', // A2 module
	'kohana-sitemap' =>	MODPATH.'kohana-sitemap', // ThePixelDeveloper Sitemap module

	'greor-core' => MODPATH.'greor/core', // Common helpers
	'greor-email' => MODPATH.'greor/email', // Swift Mailer
	'greor-thumb' => MODPATH.'greor/thumb', // Image thumb helper
	'greor-orm-helper' => MODPATH.'greor/orm-helper', // ORM Helper
	'greor-main' => MODPATH.'greor/main', // Main module
	
	'greor-feedback' => MODPATH.'greor/feedback', // Feedback module
	'greor-news' => MODPATH.'greor/news', // News module
	'greor-photo' => MODPATH.'greor/photo', // News module
	'greor-blog' => MODPATH.'greor/blog', // Blog module
));

Ku_Dir::$default_dir_chmod = 0775;
