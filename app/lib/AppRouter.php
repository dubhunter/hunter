<?php

use Phalcon\Text;

class AppRouter extends Phalcon\Mvc\Router\Group {

	protected static $routes = array(
		'/' => 'home',
		'/message' => 'message',
		'/v1/light' => 'v1Light',
	);

	public function initialize() {
		foreach (self::$routes as $route => $controller) {
			$name = str_replace('_', '-', Text::uncamelize($controller));
			$this->add($route, $controller)->setName($name);
		}
	}

}
