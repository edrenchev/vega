<?php
namespace City\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use City\Service\CityManager;

class CityManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
		$sessionSearchCityForm = $container->get('SearchClientForm');

        return new CityManager($entityManager, $sessionSearchCityForm);
    }
}