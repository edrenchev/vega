<?php
namespace Client\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Client\Service\ClientManager;
use Client\Controller\ClientController;

class ClientControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $clientManager = $container->get(ClientManager::class);

        // Instantiate the controller and inject dependencies
        return new ClientController($entityManager, $clientManager);
    }
}