<?php

use Dubhunter\HunterLight\Lib\AppServices;
use Dubhunter\Talon\Mvc\RestApplication;
use Phalcon\Di\FactoryDefault as DI;
use Phalcon\Loader;

define('APP_DIR', realpath('../app') . '/');
define('CONFIG_DIR', APP_DIR . 'config/');
define('PUBLIC_DIR', realpath(APP_DIR . '../public') . '/');
define('VENDOR_DIR', realpath(APP_DIR . '../vendor') . '/');

try {
	$loader = new Loader();
	$loader->registerNamespaces([
		'Dubhunter\\HunterLight\\Controllers' => APP_DIR . 'controllers/',
		'Dubhunter\\HunterLight\\Lib' => APP_DIR . 'lib/',
	]);

	$loader->register();

	require VENDOR_DIR . 'autoload.php';

	$di = new Di();

	(new AppServices($di))->install();

	/**
	 * Run the application
	 */
	$app = new RestApplication($di);
	$app->handle()->send();

} catch (Exception $e) {
	echo 'Uncaught Exception: ' . get_class($e) . $e->getMessage();
}
