<?php

namespace Dubhunter\HunterLight\Controllers\V1;

use Dubhunter\Talon\Http\Response\Json as JsonResponse;
use Exception;

class TempController extends ApiController {

	public function post() {
		try {
			$temp = strtolower(trim($this->request->getPost('data')));
			$this->setOutsideTemp($temp);
			return JsonResponse::ok([
				'temp' => $this->getOutsideTemp(),
			]);
		} catch (Exception $e) {
			return JsonResponse::error([
				'error' => $e->getMessage(),
			]);
		}
	}

}
