<?php
namespace City\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use City\Service\CityManager;
use City\Controller\CityController;

class CityControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $cityManager = $container->get(CityManager::class);

        // Instantiate the controller and inject dependencies
        return new CityController($entityManager, $cityManager);
    }
}