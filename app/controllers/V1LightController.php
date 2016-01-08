<?php

use Talon\Http\Response\Json as JsonResponse;

class V1LightController extends V1ApiController {

	public function get() {
		try {
			return JsonResponse::ok(array(
				'color' => $this->getColor(),
				'days' => $this->getDays(),
				'time' => time(),
			));
		} catch (Exception $e) {
			return JsonResponse::error(array(
				'error' => $e->getMessage(),
			));
		}
	}

}
