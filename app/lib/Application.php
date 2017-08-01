<?php

use Talon\Http\Response;
use Talon\Http\Response\Json as JsonResponse;
use Talon\Mvc\View\Template;

class Application extends Phalcon\Mvc\Application {

	public function handle($uri = null) {
		/** @var Response $response */
		$response = parent::handle($uri);

		if ($response->getContent()) {
			return $response;
		}

		switch ($response->getStatusCode()) {
			case Response::HTTP_NOT_FOUND:
			case Response::HTTP_METHOD_NOT_ALLOWED:
			case Response::HTTP_UNAUTHORIZED:
			case Response::HTTP_FORBIDDEN:
			case Response::HTTP_INTERNAL_SERVER_ERROR:
				if ($this->request->isAjax() || $response instanceof JsonResponse) {
					$response->setContent([
						'error' => $response->getStatusMessage(),
					]);
				} else {
					$template = new Template($this->view, 'error');
					$template->set('code', $response->getStatusCode());
					$template->set('message', $response->getStatusMessage());
					$response->setContent($template->render());
				}
			break;
			default:
				break;
		}

		return $response;
	}

}
