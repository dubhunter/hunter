<?php

use Talon\Http\Response;

class HomeController extends SiteController {

	public function get() {
		$template = $this->getTemplate('home');

		$template->set('color', $this->getColor());
		$template->set('days', $this->getDays());

		return Response::ok($template);
	}

}
