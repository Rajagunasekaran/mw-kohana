<?php defined('SYSPATH') OR die('No direct access allowed.');
return array(
	'native' => array(
		'name' => Cookie::$salt,
		'lifetime' => Cookie::$expiration,
	),
	'cookie' => array(
		'name' => Cookie::$salt,
		'encrypted' => TRUE,
		'lifetime' => Cookie::$expiration,
	),
	'database' => array(
        'name' => Cookie::$salt,
        'encrypted' => TRUE,
        'lifetime' => Cookie::$expiration,
        'group' => 'default',
        'table' => 'user_sessions',
        'columns' => array(
            'session_id'  => 'session_id',
            'last_active' => 'last_active',
            'contents'    => 'contents'
        ),
        'gc' => 500,
    ),
);