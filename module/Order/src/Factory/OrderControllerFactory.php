<?php
namespace Order\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Order\Service\OrderManager;
use Order\Controller\OrderController;

class OrderControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $orderManager = $container->get(OrderManager::class);

        // Instantiate the controller and inject dependencies
        return new OrderController($entityManager, $orderManager);
    }
}