<?php
namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;

class Module {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * This method is called once the MVC bootstrapping is complete.
     */
    public function onBootstrap(MvcEvent $event) {
        $application = $event->getApplication();
        $serviceManager = $application->getServiceManager();

        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one to avoid passing the
        // session manager as a dependency to other models.
        $sessionManager = $serviceManager->get(SessionManager::class);
    }
}
