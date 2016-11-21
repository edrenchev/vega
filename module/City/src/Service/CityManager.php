<?php
namespace City\Service;

use City\Entity\City;

class CityManager {
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;

	/**
	 * Entity manager.
	 * @var Zend\Session\Container;
	 */
	private $sessionSearchCityForm;
    /**
     * Constructor.
     */
    public function __construct($entityManager, $sessionSearchCityForm) {
        $this->entityManager = $entityManager;
		$this->sessionSearchCityForm = $sessionSearchCityForm;
    }

    public function addNewCity($data) {

        $city = new City();
        $city->setName($data['name']);

        $this->entityManager->persist($city);

        $this->entityManager->flush();
    }

    public function updateCity(City $city, $data) {
        $city->setName($data['name']);

        $this->entityManager->flush();
    }

    public function removeCity(City $city) {

        $this->entityManager->remove($city);

        $this->entityManager->flush();
    }

	public function addSearchDataInSession($data) {
		unset($data['searchSubmit'], $data['clearSubmit']);
		$this->sessionSearchCityForm->data = $data;
	}

	public function getSearchDataFromSession() {
		if(isset($this->sessionSearchCityForm->data)) {
			return $this->sessionSearchCityForm->data;
		}

		return [];
	}

	public function clearSearchDataInSession() {
		unset($this->sessionSearchCityForm->data);
	}
}