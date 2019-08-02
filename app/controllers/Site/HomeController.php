<?php

namespace Dubhunter\HunterLight\Controllers\Site;

use Dubhunter\HunterLight\Lib\Cache;
use Dubhunter\Talon\Http\Response;
use Exception;

class HomeController extends SiteController {

	public function get() {
		$template = $this->getTemplate('home');

		$template->set('color', $this->getColor());
		$template->set('days', $this->getDays());
		$template->set('age', $this->getAge());
		$template->set('insideTemp', intval(round($this->getInsideTemp())));
		$template->set('outsideTemp', intval(round($this->getOutsideTemp())));

		$template->set('colors', Cache::$colors);

		return Response::ok($template);
	}

	public function post() {
		try {
			if (!$this->security->checkToken()) {
				throw new Exception('Something went wrong. Please try again.');
			}

			$this->setColor($this->request->getPost('color', 'alphanum'));
		} catch (Exception $e) {
			$this->flash->error($e->getMessage());
		}
		return Response::temporaryRedirect(['for' => 'home']);
	}

}
