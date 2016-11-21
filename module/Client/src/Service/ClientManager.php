<?php
namespace Client\Service;

use Client\Entity\Client;

class ClientManager {
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

    /**
     * Entity manager.
     * @var Zend\Session\Container;
     */
    private $sessionSearchClientForm;

    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionSearchClientForm) {
        $this->entityManager = $entityManager;
        $this->sessionSearchClientForm= $sessionSearchClientForm;
    }

    public function addNewClient($data) {
        // Create new Post entity.
        $client = new Client();
        $client->setFirstName($data['first_name']);
        $client->setMiddleName($data['middle_name']);
        $client->setLastName($data['last_name']);
        $client->setEmail($data['email']);
        $client->setPhone($data['phone']);

        $client->setCityId($this->entityManager->getRepository(\City\Entity\City::class)->findOneById($data['city_id']));
//        $client->setCityId($data['city_id']);
        $client->setAddress($data['address']);
        $client->setMbps($data['mbps']);
        $client->setMonthlyPrice($data['monthly_price']);
        $client->setPayday($data['payday']);
        $client->setStatus($data['status']);
        $client->setJoinDate($data['join_date']);
        $currentDate = date('Y-m-d H:i:s');
        $client->setCreatedAt($currentDate);

        $this->entityManager->persist($client);

        $this->entityManager->flush();
    }

    public function updateClient(Client $client, $data) {
        $client->setFirstName($data['first_name']);
        $client->setMiddleName($data['middle_name']);
        $client->setLastName($data['last_name']);
        $client->setEmail($data['email']);
        $client->setPhone($data['phone']);

        $client->setCityId($this->entityManager->getRepository(\City\Entity\City::class)->findOneById($data['city_id']));
//        $client->setCityId($data['city_id']);
        $client->setAddress($data['address']);
        $client->setMbps($data['mbps']);
        $client->setMonthlyPrice($data['monthly_price']);
        $client->setPayday($data['payday']);
		$client->setStatus($data['status']);
        $client->setJoinDate($data['join_date']);

        $this->entityManager->flush();
    }

    public function removeClient(Client $client) {

        $this->entityManager->remove($client);

        $this->entityManager->flush();
    }

    public function addSearchDataInSession($data) {
        unset($data['searchSubmit'], $data['clearSubmit']);
        $this->sessionSearchClientForm->data = $data;
    }

    public function getSearchDataFromSession() {
        if(isset($this->sessionSearchClientForm->data)) {
            return $this->sessionSearchClientForm->data;
        }

        return [];
    }

    public function clearSearchDataInSession() {
        unset($this->sessionSearchClientForm->data);
    }

}