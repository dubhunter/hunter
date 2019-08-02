<?php

namespace Dubhunter\HunterLight\Controllers;

use DateTime;
use Dubhunter\Talon\Mvc\RestController;
use Dubhunter\Talon\Mvc\View\Template;
use Exception;

class BaseController extends RestController {

	const DOB = 1460170560;

	/**
	 * @return array
	 */
	protected function getAppGlobal() {
		$app = [
			'values' => $this->request->get(null, 'string'),
		];
		return $app;
	}

	/**
	 * @param string $filename
	 * @return Template
	 * @throws Exception
	 */
	protected function getTemplate($filename) {
		$template = parent::getTemplate($filename);
		$template->set('app', $this->getAppGlobal());
		return $template;
	}

	/**
	 * @return Cache
	 */
	protected function cache() {
		return $this->getDI()->get('cache');
	}

	/**
	 * @param string $color
	 * @throws Exception
	 */
	protected function setColor($color) {
		$this->cache()->setColor($color);
	}

	/**
	 * @return mixed
	 */
	protected function getColor() {
		return $this->cache()->getColor();
	}

	/**
	 * @param string $temp
	 */
	protected function setInsideTemp($temp) {
		$this->cache()->setInsideTemp($temp);
	}

	/**
	 * @return mixed
	 */
	protected function getInsideTemp() {
		return $this->cache()->getInsideTemp();
	}

	/**
	 * @param string $temp
	 */
	protected function setOutsideTemp($temp) {
		$this->cache()->setOutsideTemp($temp);
	}

	/**
	 * @return mixed
	 */
	protected function getOutsideTemp() {
		return $this->cache()->getOutsideTemp();
	}

	/**
	 * @return int
	 */
	protected function getDays() {
		return intval(abs(time() - self::DOB) / 86400);
	}

	/**
	 * @return array|null
	 */
	protected function getAge() {
		try {
			$birth = (new DateTime())->setTimestamp(self::DOB);
			$now = (new DateTime())->setTimestamp(time());
			$diff = $now->diff($birth);
			return [
				'years' => $diff->format('%y'),
				'months' => $diff->format('%m'),
				'days' => $diff->format('%d'),
			];
		} catch (Exception $e) {
			return null;
		}

	}

	/**
	 * Validate the
	 * @return bool
	 */
	protected function validTwilioRequest() {
		$twilio = $this->getDI()->get('config')->get('twilio');

		$str = $this->getUrl(true);
		if (count($this->request->getQuery())) {
			$str = $this->buildUrl($str, $this->request->getQuery());
		}
		if ($this->request->isPost()) {
			$data = $this->request->getPost();
			ksort($data);
			foreach ($data as $k => $v) {
				$str .= $k . $v;
			}
		}
		$str = base64_encode(hash_hmac('sha1', $str, $twilio->authToken, true));
		return $str == $this->request->getHeader('X_TWILIO_SIGNATURE');
	}

}
