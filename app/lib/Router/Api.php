<?php

namespace Dubhunter\HunterLight\Lib\Router;

class Api extends App {

	const PREFIX = '/v1';
	const ROUTES = [
		'/call' => 'V1\\call',
		'/message' => 'V1\\message',
		'/light' => 'V1\\light',
		'/temp' => 'V1\\temp',
	];

}
