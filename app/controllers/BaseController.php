<?php

use Talon\Http\Response;
use Talon\Http\Response\Json as JsonResponse;
use Talon\Http\RestRequest;
use Talon\Mvc\View\Template;
use Phalcon\Cache\Backend\Memcache as Memcache;

class BaseController extends Phalcon\Mvc\Controller {

	const DOB = 1460876400;

	protected static $colors = array(
		'off',
		'rainbow',
		'red',
		'pink',
		'purple',
		'blue',
		'aqua',
		'green',
		'yellow',
		'orange',
		'gray',
	);

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
		return http_build_url($url, array('query' => http_build_query($params)));
	}

	/**
	 * @return array
	 */
	protected function getAppGlobal() {
		$app = array(
			'values' => $this->request->get(null, 'string'),
		);
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
	 * @param string $key
	 * @param mixed $variable
	 */
	protected function cacheSet($key, $variable) {
		/** @var Memcache $cache */
		$cache = $this->getDI()->get('modelsCache');
		$cache->save($key, $variable);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function cacheGet($key, $default = null) {
		/** @var Memcache $cache */
		$cache = $this->getDI()->get('modelsCache');
		return $cache->exists($key) ? $cache->get($key) : $default;
	}

	/**
	 * @param string $color
	 * @throws Exception
	 */
	protected function setColor($color) {
		if (!in_array($color, self::$colors)) {
			throw new Exception('Invalid Color');
		}

		$this->cacheSet('color', $color);
	}

	/**
	 * @return mixed
	 */
	protected function getColor() {
		return $this->cacheGet('color');
	}

	/**
	 * @param string $temp
	 */
	protected function setTemp($temp) {
		$this->cacheSet('temp', $temp);
	}

	/**
	 * @return mixed
	 */
	protected function getTemp() {
		return $this->cacheGet('temp');
	}

	/**
	 * @return int
	 */
	protected function getDays() {
		return intval(abs(time() - self::DOB) / 86400);
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
