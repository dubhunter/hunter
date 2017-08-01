<?php

use Talon\Http\Response\Json as JsonResponse;

class V1TempController extends V1ApiController {

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
