<?php

namespace Dubhunter\HunterLight\Controllers\V1;

use Dubhunter\HunterLight\Controllers\BaseController;
use Dubhunter\Talon\Http\Response;

class ApiController extends BaseController {

	public function initialize() {
		if ($this->request->getHeader('ORIGIN')) {
			Response::addDefaultHeader('Access-Control-Allow-Origin', $this->request->getHeader('ORIGIN'));
			Response::addDefaultHeader('Access-Control-Allow-Credentials', 'true');
		} else {
			Response::addDefaultHeader('Access-Control-Allow-Origin', '*');
		}
	}

	/**
	 * @return Response
	 */
	public function options() {
		$response = Response::ok();
		$response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
		$response->setHeader('Access-Control-Max-Age', '604800');
		$response->setHeader('Access-Control-Allow-Headers', 'authorization, x-requested-with, x-requested-by');
		return $response;
	}

}
