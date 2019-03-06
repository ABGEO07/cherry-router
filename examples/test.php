<?php

//Include autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use Cherry\Router;

define('ROUTES_FILE', __DIR__ . '/src/config/routes.json');
define('CONTROLLERS_PATH', __DIR__ . '/src/controllers');

$router = new Router(ROUTES_FILE, CONTROLLERS_PATH);