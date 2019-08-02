<?php

namespace Dubhunter\HunterLight\Controllers\V1;

use Dubhunter\Talon\Http\Response;
use Dubhunter\Talon\Http\Response\Twiml as TwimlResponse;

class CallController extends ApiController {

	public function post() {
		if (!$this->validTwilioRequest()) {
			return Response::forbidden('Invalid Signature');
		}

		$template = $this->getTemplate('twiml/no-call');
		return TwimlResponse::ok($template);
	}

}
