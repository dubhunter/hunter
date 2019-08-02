<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault\Cli as Di;
use Phalcon\Cli\Console as ConsoleApp;
use Dubhunter\HunterLight\Lib\CliServices;

define('APP_DIR', realpath(dirname(__FILE__)) . '/');
define('CONFIG_DIR', APP_DIR . 'config/');
define('PUBLIC_DIR', realpath(APP_DIR . '../public') . '/');
define('VENDOR_DIR', realpath(APP_DIR . '../vendor') . '/');

try {
	/**
	 * Register the autoloader and tell it to register the tasks directory
	 */
	$loader = new Loader();
	$loader->registerNamespaces([
		'Dubhunter\\HunterLight\\Lib' => APP_DIR . 'lib/',
		'Dubhunter\\HunterLight\\Tasks' => APP_DIR . 'tasks/',
	]);

	$loader->register();

	require VENDOR_DIR . 'autoload.php';

	$di = new Di();

	(new CliServices($di))->install();

	/**
	 * Process the console arguments
	 */
	$arguments = [];
	foreach ($argv as $k => $arg) {
		if ($k == 1) {
			$arguments['task'] = $arg;
		} elseif ($k == 2) {
			$arguments['action'] = $arg;
		} elseif ($k >= 3) {
			$arguments['params'][] = $arg;
		}
	}

	define('CURRENT_TASK', (isset($argv[1]) ? $argv[1] : null));
	define('CURRENT_ACTION', (isset($argv[2]) ? $argv[2] : null));

	$console = new ConsoleApp($di);
	$console->handle($arguments);
} catch (\Phalcon\Exception $e) {
	echo $e->getMessage() . PHP_EOL;
	exit(255);
}
