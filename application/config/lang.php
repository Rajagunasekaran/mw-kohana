<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'driver'       => 'ORM',
	'hash_method'  => 'sha256',
	'hash_key'     => '2DC2211279794903AC4A70AFE66E83B37042D8E6402D04A849910D715299DA60',
	'lifetime'     => 1209600,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',
	'regenerate'   => '10',
	'gc_probability' => 2
);
