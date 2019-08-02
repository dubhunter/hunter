<?php

namespace Dubhunter\HunterLight\Controllers\V1;

use Dubhunter\Talon\Http\Response;
use Dubhunter\Talon\Http\Response\Twiml as TwimlResponse;

class MessageController extends ApiController {

	public function post() {
		try {
			if (!$this->validTwilioRequest()) {
				return Response::forbidden('Invalid Signature');
			}

			$color = strtolower(trim($this->request->getPost('Body')));

			$this->setColor($color);

			$template = $this->getTemplate('twiml/thanks');
			return TwimlResponse::ok($template);
		} catch (Exception $e) {
			$template = $this->getTemplate('twiml/bad-color');
			return TwimlResponse::ok($template);
		}
	}

}
