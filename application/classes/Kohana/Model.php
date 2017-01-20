<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Email message building and sending.
 *
 * @package    Kohana
 * @category   Email
 * @author     Kohana Team
 * @copyright  (c) 2007-2011 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
class Kohana_Model{
	public static function instance($path, $namespace = 'default')
	{
		static $instances;
		
		// If instances is null, that means we have to inialize it as an array
		if (is_null($instances)) 
		{
			$instances = array($namespace => array());
		}
		
		// Let's get the class name based on the path
		$classname = str_replace('/', '_', $path);
		
		if (class_exists($classname)) 
		{
			if (isset($instances[$namespace][$path]) AND $instances[$namespace][$path] instanceof $classname) {
				return $instances[$namespace][$path];
			}
			
			return $instances[$namespace][$path] = new $classname;
		}
		else
		{
			throw new Kohana_Exception('Class :classname does not exist',
				array(':classname' => $classname));
		}
	}
}