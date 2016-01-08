<?php

use Talon\Http\Response;
use Talon\Http\Response\Twiml as TwimlResponse;

class CallController extends BaseController {

	public function post() {
		if (!$this->validTwilioRequest()) {
			return Response::forbidden('Invalid Signature');
		}

		$template = $this->getTemplate('twiml/no-call');
		return TwimlResponse::ok($template);
	}

}