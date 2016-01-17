<?php

use Talon\Http\Response\Json as JsonResponse;

class V1TempController extends V1ApiController {

	public function post() {
		try {
			$temp = strtolower(trim($this->request->getPost('data')));
			$this->setTemp($temp);
			return JsonResponse::ok(array(
				'temp' => $this->getTemp(),
			));
		} catch (Exception $e) {
			return JsonResponse::error(array(
				'error' => $e->getMessage(),
			));
		}
	}

}
