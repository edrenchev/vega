<?php

namespace City\Controller;

use City\Entity\City;
use City\Form\CityForm;
use City\Form\SearchForm;
use City\Service\CityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PageAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

class CityController extends AbstractActionController {

    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    public $entityManager;

    /**
     * Post manager.
     * @var Application\Service\PostManager
     */
    private $cityManager;

    public function __construct($entityManager, CityManager $cityManager) {
        $this->entityManager = $entityManager;
        $this->cityManager = $cityManager;
    }

    public function indexAction() {

		$form = new SearchForm();

		$sessionSearchCityFormData = $this->cityManager->getSearchDataFromSession();

		$isOpenSearchForm = false;
		if ($this->getRequest()->isPost()) {

			$data = $this->params()->fromPost();

			if (isset($data['searchSubmit']) && !empty($data['searchSubmit'])) {
				$this->cityManager->addSearchDataInSession($data);
			} elseif (isset($data['clearSubmit']) && !empty($data['clearSubmit'])) {
				$this->cityManager->clearSearchDataInSession();
			}

			return $this->redirect()->toRoute('cities');

		}

		$page = (int)$this->params()->fromQuery('page', 1);
		$page = ($page < 1) ? 1 : $page;


		if (!empty($sessionSearchCityFormData)) {
			$form->setData($sessionSearchCityFormData);
			$cities = $this->entityManager->getRepository(City::class)->findCityBySearchData($sessionSearchCityFormData);
			$isOpenSearchForm = true;
		} else {
			$cities = $this->entityManager->getRepository(City::class)->getCityOrderByName();
		}

		$paginator = new ZendPaginator(new PageAdapter(new ORMPaginator($cities)));
		$paginator->setCurrentPageNumber($page)->setItemCountPerPage(20);

        return new ViewModel([
			'paginator' => $paginator,
            'cityManager' => $this->cityManager,
			'searchForm' => $form,
			'isOpenSearchForm' => $isOpenSearchForm,
        ]);
    }

    public function addAction() {
        $form = new CityForm();

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {

                $data = $form->getData();

                $this->cityManager->addNewCity($data);

                return $this->redirect()->toRoute('cities');
            }
        }

        return new ViewModel([
            'form' => $form
        ]);
    }

    public function editAction() {

        $form = new CityForm();

        $cityId = (int)$this->params()->fromRoute('id', -1);

        if ($cityId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $city = $this->entityManager->getRepository(City::class)
            ->findOneById($cityId);
        if ($city == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        if ($this->getRequest()->isPost()) {

            $data = $this->params()->fromPost();

            $form->setData($data);
            if ($form->isValid()) {

                $data = $form->getData();

                $this->cityManager->updateCity($city, $data);

                return $this->redirect()->toRoute('cities');
            }
        } else {
            $data = [
                'name' => $city->getName(),
            ];

            $form->setData($data);
        }

        return new ViewModel([
            'form' => $form,
            'city' => $city
        ]);
    }

    public function deleteAction() {
        $cityId = (int)$this->params()->fromRoute('id', -1);

        if ($cityId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $city = $this->entityManager->getRepository(City::class)
            ->findOneById($cityId);
        if ($city == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $this->cityManager->removeCity($city);

        return $this->redirect()->toRoute('cities');

    }

}
