<?php

use Phalcon\Cache\Backend\Libmemcached as Memcache;

class Cache {

	const TEMP_LIFETIME = 3600;

	protected static $colors = [
		'off',
		'clock',
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
	];

	protected $backend;

	/**
	 * @param $backend Memcache
	 */
	public function __construct($backend) {
		$this->backend = $backend;
	}

	/**
	 * @param string $key
	 * @param mixed $variable
	 * @param int $lifetime
	 */
	protected function cacheSet($key, $variable, $lifetime = null) {
		$this->backend->save($key, $variable, $lifetime);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @param int $lifetime
	 * @return mixed
	 */
	protected function cacheGet($key, $default = null, $lifetime = null) {
		return $this->backend->exists($key) ? $this->backend->get($key, $lifetime) : $default;
	}

	/**
	 * @param string $color
	 * @throws Exception
	 */
	public function setColor($color) {
		if (!in_array($color, self::$colors)) {
			throw new Exception('Invalid Color');
		}

		$this->cacheSet('color', $color);
	}

	/**
	 * @return mixed
	 */
	public function getColor() {
		return $this->cacheGet('color');
	}

	/**
	 * @param string $temp
	 */
	public function setInsideTemp($temp) {
		$this->cacheSet('insideTemp', $temp, self::TEMP_LIFETIME);
	}

	/**
	 * @return mixed
	 */
	public function getInsideTemp() {
		return $this->cacheGet('insideTemp', null, self::TEMP_LIFETIME);
	}

	/**
	 * @param string $temp
	 */
	public function setOutsideTemp($temp) {
		$this->cacheSet('outsideTemp', $temp, self::TEMP_LIFETIME);
	}

	/**
	 * @return mixed
	 */
	public function getOutsideTemp() {
		return $this->cacheGet('outsideTemp', null, self::TEMP_LIFETIME);
	}


}
