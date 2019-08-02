<?php

namespace Dubhunter\HunterLight\Lib;

use Phalcon\CLI\Dispatcher;
use Phalcon\CLI\Router;

class CliServices extends AppServices {

	/**
	 * Setting up router
	 */
	protected function router() {
		return function () {
			return new Router();
		};
	}

	/**
	 * Setting up custom Dispatcher
	 */
	protected function dispatcher() {
		return function () {
			$dispatcher = new Dispatcher();
			$dispatcher->setDefaultNamespace('Dubhunter\\HunterLight\\Tasks');
			return $dispatcher;
		};
	}

}
