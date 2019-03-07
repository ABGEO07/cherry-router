<?php

//Include autoloader
require_once __DIR__ . '/../vendor/autoload.php';

define('__ROOT__', __DIR__);
define('CONFIG_FILE', __DIR__ . '/config/config.json');

//TODO: Create class for defining application config parameters
$config = file_get_contents(CONFIG_FILE)
    or die("Unable to open config file!");

$config = json_decode($config, 1);

foreach ($config as $k => $v)
    define($k, __DIR__ . '/' . $v);

use Cherry\Router;

$router = new Router();