<?php

namespace CityTest\Controller;

use City\Controller\CityController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class CityControllerTest extends AbstractHttpControllerTestCase {
	protected $traceError = true;

	public function setUp() {
		// The module configuration should still be applicable for tests.
		// You can override configuration here with test case specific values,
		// such as sample view templates, path stacks, module_listener_options,
		// etc.
		$configOverrides = [];

		$this->setApplicationConfig(ArrayUtils::merge(
		// Grabbing the full application configuration:
			include __DIR__ . '/../../../../config/application.config.php',
			$configOverrides
		));


		parent::setUp();

		$services = $this->getApplicationServiceLocator();
		$config = $services->get('config');

		$config['access_filter']['controllers'][CityController::class] = [
			['actions' => ['index'], 'allow' => '*'],
		];
		$services->setAllowOverride(true);
		$services->setService('config', $config);
		$services->setAllowOverride(false);
	}

	public function testIndexActionCanBeAccessed() {
		$this->dispatch('/cities');
		$this->assertResponseStatusCode(200);
		$this->assertModuleName('City');
		$this->assertControllerName(CityController::class);
		$this->assertControllerClass('CityController');
		$this->assertMatchedRouteName('cities');
	}
}