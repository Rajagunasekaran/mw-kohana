<?php defined('SYSPATH') OR die('No direct access allowed.');
return array(
    'browse_for_fields' => array(),
    'ignored_fields' => array(),
    'protected_fields' => array(
        'user' => array('password'),
    ),
    'redirect' => "/admin/dashboard/",
    'modules' => array(),
    'default_tab' => NULL,
    'null_value' => "{NULL_ON_PURPOSE}",
);