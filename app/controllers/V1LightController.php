<?php

use Talon\Http\Response\Json as JsonResponse;

class V1LightController extends V1ApiController {

	public function get() {
		try {
			$data = [
				'color' => $this->getColor(),
				'days' => $this->getDays(),
				'age' => $this->getAge(),
				'time' => time(),
				'offset' => date('Z'),
			];
			if ($this->getInsideTemp()) {
				$data['itemp'] = $this->getInsideTemp();
			}
			if ($this->getOutsideTemp()) {
				$data['otemp'] = $this->getOutsideTemp();
			}
			return JsonResponse::ok($data);
		} catch (Exception $e) {
			return JsonResponse::error([
				'error' => $e->getMessage(),
			]);
		}
	}

}
