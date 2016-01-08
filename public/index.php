<?php

use Talon\Http\RestRequest;
use Talon\Mvc\RestDispatcher;
use Phalcon\Loader;
use Phalcon\Security;
use Phalcon\DI\FactoryDefault as DI;
use Phalcon\Assets\Manager as AssetManager;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Cache\Frontend\Data as CacheFrontend;
use Phalcon\Cache\Backend\Libmemcached as Memcache;
use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\ViewInterface as ViewInterface;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Session\Adapter\Libmemcached as Session;

define('APP_DIR', realpath('../app') . '/');
define('PUBLIC_DIR', realpath('../public') . '/');
define('VENDOR_DIR', realpath('../vendor') . '/');

try {
	$loader = new Loader();
	$loader->registerDirs(array(
		APP_DIR . 'controllers/',
		APP_DIR . 'lib/',
		APP_DIR . 'models/',
	))->register();

	require VENDOR_DIR . 'autoload.php';

	$di = new DI();

	/**
	 * Setting up the credentials config
	 */
	$di->set('config', function () {
		return new Ini(APP_DIR . 'config/config.ini');
	}, true);

	/**
	 * Setting up the view component
	 */
	$di->set('view', function () {
		$view = new View();
		$view->setViewsDir(APP_DIR . 'views/');

		$view->registerEngines(array(
			'.volt' => function ($view, $di) {
				$env = $di->get('config')->get('environment');

				/** @var ViewInterface|View $view */
				$volt = new Volt($view, $di);
				$volt->setOptions(array(
					'compiledPath' => function ($templatePath) use ($view) {
						$dir = rtrim(sys_get_temp_dir(), '/') . '/volt-cache';
						if (!is_dir($dir)) {
							mkdir($dir);
						}
						return $dir . '/hoothoot-tv%'. str_replace('/', '%', str_replace($view->getViewsDir(), '', $templatePath)) . '.php';
					},
					'compileAlways' => $env->realm != 'prod',
				));

				return $volt;
			},
		));

		return $view;
	}, true);

	/**
	 * Setting up the asset manager
	 */
	$di->set('assets', function () {
		$assetManager = new AssetManager();
		return $assetManager;
	}, true);

	/**
	 * Setting up the model cache
	 */
	$di->set('modelsCache', function() use ($di) {
		$memcacheConfig = $di->get('config')->get('memcache');
		$frontCache = new CacheFrontend(array(
			'lifetime' => $memcacheConfig->lifetimeModels,
		));
		return new Memcache($frontCache, array(
			'servers' => array(
				array(
					'host' => $memcacheConfig->host,
					'port' => $memcacheConfig->port,
					'weight' => 1,
				),
			),
			'client' => array(),
		));
	}, true);

	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function () use ($di) {
		$memcacheConfig = $di->get('config')->get('memcache');
		$session = new Session(array(
			'servers' => array(
				array(
					'host' => $memcacheConfig->host,
					'port' => $memcacheConfig->port,
					'weight' => 1,
				),
			),
			'client' => array(),
			'lifetime' => $memcacheConfig->lifetimeSession,
			'prefix' => $memcacheConfig->prefix,
		));
		$session->start();

		return $session;
	});

	/**
	 * Setting up the flash service
	 */
	$di->set('flash', function() {
		return new FlashSession(array(
			'notice' => 'alert alert-info',
			'success' => 'alert alert-success',
			'warning' => 'alert alert-warning',
			'error' => 'alert alert-danger',
		));
	});

	/**
	 * Setting up custom Request object
	 */
	$di->set('request', function () {
		return new RestRequest();
	});

	/**
	 * Setting up custom Dispatcher
	 */
	$di->set('dispatcher', function () {
		return new RestDispatcher();
	});

	/**
	 * Setting up router and mounting AppRouter
	 */
	$di->set('router', function () {
		$router = new Router(false);

		$router->removeExtraSlashes(true);

		$router->notFound(array(
			'controller' => 'error404',
		));

		$router->mount(new AppRouter());

		return $router;
	});

	/**
	 * Run the application
	 */
	$app = new Application($di);
	$app->useImplicitView(false);
	$app->handle()->send();

} catch (Exception $e) {
	echo 'Uncaught Exception: ' . get_class($e) . $e->getMessage();
}
