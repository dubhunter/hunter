<?php

use GuzzleHttp\Client;

class LightTask extends Phalcon\CLI\Task {

	/**
	 * @return Cache
	 */
	protected function cache() {
		return $this->getDI()->get('cache');
	}

	public function fetchTempAction() {
		echo 'FETCHING TEMPERATURE... ' . PHP_EOL;

		$bayweb = $this->getDI()->get('config')->get('bayweb');

		$client = new Client();
		$response = $client->get(
			'http://api.bayweb.com/v2/',
			[
				'query' => [
					'id' => $bayweb->id,
					'key' => $bayweb->key,
					'action' => 'data',
				],
			]
		);

		$body = $response->getBody();

		if ($response->getStatusCode() == 200) {
			$data = $data = json_decode($body, true);
			print_r($data);
			echo 'INSIDE TEMP: ' . $data['iat'] . PHP_EOL;
			echo 'OUTSIDE TEMP: ' . $data['oat'] . PHP_EOL;
			$this->cache()->setInsideTemp($data['iat']);
			$this->cache()->setOutsideTemp($data['oat']);
		} else {
			echo 'ERROR: ' . $body;
		}

		echo PHP_EOL . 'FETCH COMPLETE' . PHP_EOL;
	}

}
