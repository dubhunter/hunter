<?php

namespace Dubhunter\HunterLight\Tasks;

use Dubhunter\HunterLight\Lib\Cache;
use GuzzleHttp\Client;
use Phalcon\CLI\Task;

class LightTask extends Task {

	/**
	 * @return Cache
	 */
	protected function cache() {
		return $this->getDI()->get('cache');
	}

	protected function nestRequest($url = 'https://developer-api.nest.com/') {
		$nest = $this->getDI()->get('config')->get('nest');

		$client = new Client();
		$response = $client->get(
			$url,
			[
				'allow_redirects' => false,
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $nest->accessToken,
				],
			]
		);

		if ($response->getStatusCode() == '307') {
			return $this->nestRequest($response->getHeader('Location')[0]);
		}

		return $response;
	}

	protected function darkskyRequest() {
		$darksky = $this->getDI()->get('config')->get('darksky');

		$client = new Client();
		return $client->get('https://api.darksky.net/forecast/' . $darksky->key . '/' . $darksky->location);
	}

	public function fetchInsideTempAction() {
		echo 'FETCHING INSIDE TEMPERATURE... ' . PHP_EOL;

		$response = $this->nestRequest();

		$body = $response->getBody();

		if ($response->getStatusCode() == 200) {
			$data = json_decode($body, true);
			$thermostat = reset($data['devices']['thermostats']);
			$temp = $thermostat['ambient_temperature_f'];
			echo 'INSIDE TEMP: ' . $temp . PHP_EOL;
			$this->cache()->setInsideTemp($temp);
		} else {
			echo 'ERROR: ' . $body;
		}

		echo PHP_EOL . 'FETCH COMPLETE' . PHP_EOL;
	}

	public function fetchOutsideTempAction() {
		echo 'FETCHING OUTSIDE TEMPERATURE... ' . PHP_EOL;

		$response = $this->darkskyRequest();

		$body = $response->getBody();

		if ($response->getStatusCode() == 200) {
			$data = json_decode($body, true);
			$temp = round($data['currently']['temperature']);
			echo 'OUTSIDE TEMP: ' . $temp . PHP_EOL;
			$this->cache()->setOutsideTemp($temp);
		} else {
			echo 'ERROR: ' . $body;
		}

		echo PHP_EOL . 'FETCH COMPLETE' . PHP_EOL;
	}

}
