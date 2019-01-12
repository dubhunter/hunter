<?php

use League\Uri\Schemes\Http as HttpUri;
use Talon\Http\Response;
use Talon\Http\Response\Json as JsonResponse;
use Talon\Http\RestRequest;
use Talon\Mvc\View\Template;

class BaseController extends Phalcon\Mvc\Controller {

	const DOB = 1460170560;

	public function options() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	public function head() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	public function get() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	public function post() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	public function put() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	public function delete() {
		return $this->request->isAjax() ? JsonResponse::methodNotAllowed() : Response::methodNotAllowed();
	}

	/**
	 * @param bool $includePath
	 * @return string
	 */
	protected function getUrl($includePath = false) {
		$url = $this->request->getScheme() . '://' . $this->request->getHttpHost();
		if ($includePath) {
			/** @var RestRequest $request */
			$request = $this->request;
			$url .= $request->getURI();
		}
		return $url;
	}

	/**
	 * @param $url
	 * @param $params
	 * @return string
	 */
	protected function buildUrl($url, $params) {
		return HttpUri::createFromString($url)->withQuery(http_build_query($params))->__toString();
	}

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
	 */
	protected function getTemplate($filename) {
		$template = new Template($this->view, $filename);
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
			$birth = new DateTime(self::DOB);
			$now = new DateTime(time());
			$diff = $now->diff($birth);
			return [
				'years' => $diff->format('%Y'),
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
