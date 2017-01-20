<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
	// Application extends the core
	require APPPATH.'classes/Kohana'.EXT;
}
else
{
	// Load empty core extension
	require SYSPATH.'classes/Kohana'.EXT;
}


/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Australia/Sydney');
//date_default_timezone_set('Pacific/Tahiti');
//echo date('Y-m-d');
/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

/**
 * Set the mb_substitute_character to "none"
 *
 * @link http://www.php.net/manual/function.mb-substitute-character.php
 */
mb_substitute_character('none');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

if (isset($_SERVER['SERVER_PROTOCOL']))
{
	// Replace the default protocol.
	HTTP::$protocol = $_SERVER['SERVER_PROTOCOL'];
}

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}else{
	Kohana::$environment = 'Kohana::DEVELOPMENT';//PRODUCTION
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
//print_r(Session::instance());die();
/*$slug_name = (Session::instance()->get('current_site_name') ? '/'.Session::instance()->get('current_site_name') : '');*/
$slug_name = '';
//echo "=============".$slug_name;die();
Kohana::init(array(
	'base_url'   => $slug_name.'/','index_file' => '','errors' =>true,'profile'=>false
));
Session::$default = 'database';
Cookie::$salt = 'myworkouts';
Cookie::$domain = FALSE; 
Cookie::$expiration = 1209600;

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

//Kohana::$config->load('constants');

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	 'auth'       => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	 'database'   => MODPATH.'database',   // Database access
	 'image'      => MODPATH.'image',      // Image manipulation
	// 'minion'     => MODPATH.'minion',     // CLI Tasks
	 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	//'phpexcel'   => MODPATH.'phpexcel', 
	'editor'	   => MODPATH.'ckeditor',
	'pagination' => MODPATH.'pagination'
	));

/**
 * Cookie Salt
 * @see  http://kohanaframework.org/3.3/guide/kohana/cookies
 * 
 * If you have not defined a cookie salt in your Cookie class then
 * uncomment the line below and define a preferrably long salt.
 */
 Cookie::$salt = 'myworkout';
/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
/*Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'welcome',
		'action'     => 'index',
	));*/

/** Admin  route**/
//'(<lang>)(/)site/<site_id>(/<slug_name>)(/<controller>)(/<param1>)'
/*Route::set('lang', '(<lang>)(/)admin(/<controller>)')
->defaults(array(
	'directory'  => 'admin',
	'controller' => 'index',
	'action'     => 'index'
));*/
Route::set('adminlogin', '(<lang>)(/)admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'index',
        'action'     => 'index',
    ));

Route::set('adminDashboard', '(<lang>)(/)admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'dashboard',
        'action'     => 'index',
    ));
Route::set('adminrecover', '(<lang>)(/)admin(/<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'index',
        'action'     => 'recover',
    ));
Route::set('userrole', '(<lang>)(/)admin(/<controller>(/<action>(/<id>)))',array("id"=>".*"))
    ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'user',
        'action'     => 'create',
        'id'     => 'register',
    ));
/** Admin route end **/
/** Front route start **/
/*Route::set('siterecover', 'site(/<id>(/<recover>))')
    ->defaults(array(
        'directory'  => 'site',
        'controller' => 'recover',
        'action'     => 'index',
    ));*/
Route::set('error', 'errors/<action>(/<message>)', array('action' => '[0-9]++', 'message' => '.+'))
->defaults(array(
    'controller' => 'errors'
));
Route::set('sitemulticontact','(<lang>)(/)site/<site_id>/contact(/<param1>)')->defaults(array(
    'directory'	 => 'site',
	'controller' => 'contact'
));	 
	 
Route::set('sitemulti','(<lang>)(/)site/<site_id>(/<slug_name>)(/<controller>)(/<param1>)')
->defaults(array(
    'directory'	 => 'site',
	'controller' => 'index',
    'action'     => 'index',
	
));

Route::set('default', '(<controller>(/<action>(/<id>(/<eid>))))', array('controller' => 'index|exercise|dashboard|search|welcome|export|general|settings|users|networks'))
	->defaults(array(
		'controller' => 'index',
		'action'     => 'index',
	));
Route::set('defaultautologin', '<site_name>/<controller>/autoredirect/<id>/<token>')
	->defaults(array(
		'controller' => 'index',
		'action'     => 'autoredirect',
	));
Route::set('croncontr', 'general/<action>')
	->defaults(array(
		'controller' => 'general',
		'action'     => 'index',
	));
Route::set('ajaxcontr', 'ajax/<action>')
	->defaults(array(
		'controller' => 'ajax',
		'action'     => 'index',
	));
Route::set('defaultsitetitle', '<site_name>')
	->defaults(array(
		'controller' => 'index',
		'action'     => 'index',
	));
Route::set('defaultsite','<site_name>/<controller>(/<action>(/<id>(/<eid>)))', array('controller' => 'index|exercise|dashboard|welcome|export|settings|users|networks'))
->defaults(array(
    'action'     => 'index',
));

Route::set('defaultsiteimage','site/<site_id>(/<slug_name>)(/<controller>(/<action>(/<id>(/<eid>(/<iid>)))))', array('controller' => 'exercise'))
->defaults(array(
    'action'     => 'exerciseimage',
));


Route::set('defaultother', '(<controller>(/<action>(/<id>(/<eid>))))', array('controller' => 'index|exercise|networks|dashboard|welcome|export|general|settings'))
	->defaults(array(
		'action'     => 'index',
	));
Route::set('imageother', '(<controller>(/<action>(/<id>(/<eid>(/<iid>)))))', array('controller' => 'exercise'))
	->defaults(array(
		'action'     => 'exerciseimage',
	));
	