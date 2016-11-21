<?php
namespace Application\Factory;

use Application\Controller\IndexController;
use Interop\Container\ContainerInterface;

use Order\Service\OrderManager;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * This is the factory for Menu view helper. Its purpose is to instantiate the
 * helper and init menu items.
 */
class IndexControllerFactory implements FactoryInterface {
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null) {
		$entityManager = $container->get('doctrine.entitymanager.orm_default');
		$orderManager = $container->get(OrderManager::class);
		// Instantiate the controller and inject dependencies
		return new IndexController($entityManager, $orderManager);
	}
}