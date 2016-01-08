<?php

use Talon\Http\Response;
use Talon\Http\Response\Json as JsonResponse;

class Error404Controller extends SiteController {

	const HTTP_STATUS_CODE = 404;

	protected function handle() {
		if ($this->request->isAjax()) {
			return JsonResponse::notFound();
		}
		return Response::notFound();
	}

	public function options() {
		return $this->handle();
	}

	public function head() {
		return $this->handle();
	}

	public function get() {
		return $this->handle();
	}

	public function post() {
		return $this->handle();
	}

	public function put() {
		return $this->handle();
	}

	public function delete() {
		return $this->handle();
	}

}