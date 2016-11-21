<?php
namespace Client\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Client\Service\ClientManager;

class ClientManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionSearchClientForm = $container->get('SearchClientForm');

        return new ClientManager($entityManager, $sessionSearchClientForm);
    }
}