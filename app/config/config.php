<?php

// Prevent direct requests to this file due to security reasons
defined('APP_INIT') or die('Access denied!');

// Return the config array
return Array(
    'general' => Array(
        'site_url'          => 'http://local.timvisee.com/app/QuizScouting/app/',
        'site_path'         => '/app/QuizScouting/app'
    ),

    'database' => Array(
        'host' => '127.0.0.1',
        'port' => 3306,
        'database' => 'scoutingquiz',
        'user' => 'root',
        'password' => 'hoihoi',
        'table_prefix' => 'quiz_'
    ),

    'cookie' => Array(
        'domain' => '',
        'path' => '/',
        'prefix' => 'carbon_'
    ),

    'hash' => Array(
        'algorithm' => 'sha256',
        'salt'  => '7cc8b7833dba0a03dd6d1401aa25262fab029862b494aff2168f1a6b35f1f406'
    ),

    'app' => Array(
        'debug' => true
    )
);
