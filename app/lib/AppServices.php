<?php

namespace Dubhunter\HunterLight\Lib;

use Dubhunter\HunterLight\Lib\Router\Api as ApiRouter;
use Dubhunter\HunterLight\Lib\Router\Site as SiteRouter;
use Dubhunter\Talon\Di\ServiceCollection;
use Dubhunter\Talon\Flash\Bootstrap as BootstrapFlash;
use Dubhunter\Talon\Http\RestRequest;
use Dubhunter\Talon\Mvc\RestDispatcher;
use Dubhunter\Talon\Mvc\RestRouter;
use Dubhunter\Talon\Mvc\View\Engine\Volt;
use Memcached;
use Phalcon\Assets\Manager as AssetManager;
use Phalcon\Cache\Backend\Libmemcached as Memcache;
use Phalcon\Cache\Frontend\Data as CacheFrontend;
use Phalcon\Config\Adapter\Ini;
use Phalcon\Di\FactoryDefault as Di;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\View\Simple as View;
use Phalcon\Mvc\ViewInterface;
use Phalcon\Session\Adapter\Libmemcached as Session;

class AppServices extends ServiceCollection {

	protected function config() {
		return function () {
			return new Ini(CONFIG_DIR . 'config.ini');
		};
	}

	/**
	 * Setting up the view component
	 */
	protected function view() {
		return function () {
			$view = new View();
			$view->setViewsDir(APP_DIR . 'views/');

			$view->registerEngines([
				'.volt' => function ($view, $di) {
					/** @var DI $di */
					$env = $di->get('config')->get('environment');

					/** @var ViewInterface|View $view */
					$volt = new Volt($view, $di);
					$volt->setOptions([
						'compiledPath' => function ($templatePath) use ($view) {
							$dir = rtrim(sys_get_temp_dir(), '/') . '/volt-cache';
							if (!is_dir($dir)) {
								mkdir($dir);
							}
							return $dir . '/hunter-light%'. str_replace('/', '%', str_replace($view->getViewsDir(), '', $templatePath)) . '.php';
						},
						'compileAlways' => $env->realm != 'prod',
					]);

					return $volt;
				},
			]);

			return $view;
		};
	}

	protected function assets() {
		return function () {
			return new AssetManager();
		};
	}

	protected function cache() {
		$di = $this->di;
		return function () use ($di) {
			$memcacheConfig = $di->get('config')->get('memcache');
			$frontend = new CacheFrontend([
				'lifetime' => $memcacheConfig->lifetimeModels,
			]);
			$backend = new Memcache($frontend, [
				'servers' => [
					[
						'host' => $memcacheConfig->host,
						'port' => $memcacheConfig->port,
						'weight' => 1,
					],
				],
				'client' => [
					Memcached::OPT_PREFIX_KEY => $memcacheConfig->prefix,
				],
			]);
			return new Cache($backend);
		};
	}

	/**
	 * Start the session the first time some component request the session service
	 */
	protected function session() {
		$di = $this->di;
		return function () use ($di) {
			$memcacheConfig = $di->get('config')->get('memcache');
			$session = new Session([
				'servers' => [
					[
						'host' => $memcacheConfig->host,
						'port' => $memcacheConfig->port,
						'weight' => 1,
					],
				],
				'client' => [],
				'lifetime' => $memcacheConfig->lifetimeSession,
				'prefix' => $memcacheConfig->prefix,
			]);
			$session->start();

			return $session;
		};
	}

	/**
	 * Setting up the flash service
	 */
	protected function flash() {
		return function () {
			return new BootstrapFlash();
		};
	}

	/**
	 * Setting up custom Request object
	 */
	protected function request() {
		return function () {
			return new RestRequest();
		};
	}

	/**
	 * Setting up custom Dispatcher
	 */
	protected function dispatcher() {
		return function () {
			return new RestDispatcher();
		};
	}

	/**
	 * Setting up custom Url object
	 */
	protected function url() {
		return function () {
			$url = new Url();
			$url->setBaseUri('/');
			return $url;
		};
	}

	/**
	 * Setting up router
	 */
	protected function router() {
		return function () {
			$router = new RestRouter();
			$router->setDefaultNamespace(SiteRouter::SPACE);
			$router->notFound('Error404');
			$router->mount(new SiteRouter());
			$router->mount(new ApiRouter());
			return $router;
		};
	}

}
