<?php

use Phalcon\Assets\Filters\Cssmin;
use Phalcon\Assets\Filters\Jsmin;

class SiteController extends BaseController {

	public function	initialize() {
		$this->assets->setOptions(array(
			'sourceBasePath' => APP_DIR . 'assets/',
			'targetBasePath' => PUBLIC_DIR
		));

		$this->assets->collection('css')
			->setSourcePath('css/')
			->setTargetPath('css/core.css')
			->setTargetUri('css/core.css')
			->addCss('bootstrap.css')
			->addCss('font-awesome.css')
			->join(true)
			->addFilter(new Cssmin());

		$this->assets->collection('js')
			->setSourcePath('js/')
			->setTargetPath('js/core.js')
			->setTargetUri('js/core.js')
			->addJs('jquery-1.10.2.min.js')
			->addJs('jquery.role.js')
			->addJs('bootstrap.js')
			->addJs('app.js')
			->join(true)
			->addFilter(new Jsmin());
	}

}
