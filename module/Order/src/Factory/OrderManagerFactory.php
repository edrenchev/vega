<?php
namespace Order\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Order\Service\OrderManager;

class OrderManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
		$sessionSearchOrderForm = $container->get('SearchClientForm');

        return new OrderManager($entityManager, $sessionSearchOrderForm);
    }
}