<?php

use GuzzleHttp\Client;

class LightTask extends Phalcon\CLI\Task {

	/**
	 * @return Cache
	 */
	protected function cache() {
		return $this->getDI()->get('cache');
	}

	public function fetchInsideTempAction() {
		echo 'FETCHING INSIDE TEMPERATURE... ' . PHP_EOL;

		$nest = $this->getDI()->get('config')->get('nest');

		$client = new Client();
		$response = $client->get(
			'https://developer-api.nest.com/',
			[
				'headers' => [
					'Content-Type' => 'application/json',
					'Authorization' => 'Bearer ' . $nest->accessToken,
				],
			]
		);

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

		$darksky = $this->getDI()->get('config')->get('darksky');

		$client = new Client();
		$response = $client->get('https://api.darksky.net/forecast/' . $darksky->key . '/' . $darksky->location);

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
