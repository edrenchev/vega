<?php

namespace Client\Controller;

use Client\Entity\Client;
use Client\Form\ClientForm;
use Client\Form\SearchForm;
use Client\Service\ClientManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PageAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;


class ClientController extends AbstractActionController {

	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager
	 */
	public $entityManager;

	/**
	 * Post manager.
	 * @var Client\Service\ClientManager
	 */
	private $clientManager;

	public function __construct($entityManager, ClientManager $clientManager) {
		$this->entityManager = $entityManager;
		$this->clientManager = $clientManager;
	}

	public function indexAction() {

		$form = new SearchForm($this->entityManager);

		$sessionSearchClientFormData = $this->clientManager->getSearchDataFromSession();

		if ($this->getRequest()->isPost()) {

			$data = $this->params()->fromPost();

			if (isset($data['searchSubmit']) && $data['searchSubmit'] == 'Search') {
				$this->clientManager->addSearchDataInSession($data);
			} elseif (isset($data['clearSubmit']) && $data['clearSubmit'] == 'Clear') {
				$this->clientManager->clearSearchDataInSession();
			}

			return $this->redirect()->toRoute('clients');

		}


		$page = (int)$this->params()->fromQuery('page', 1);
		$page = ($page < 1) ? 1 : $page;


		if (!empty($sessionSearchClientFormData)) {
			$form->setData($sessionSearchClientFormData);
			$clients = $this->entityManager->getRepository(Client::class)->findClientsBySearchData($sessionSearchClientFormData);
		} else {
			$clients = $this->entityManager->getRepository(Client::class)->getClientsOrderByJoinDate();
		}

		$paginator = new ZendPaginator(new PageAdapter(new ORMPaginator($clients)));
		$paginator->setCurrentPageNumber($page)->setItemCountPerPage(20);

		return new ViewModel([
			'paginator' => $paginator,
			'clientManager' => $this->clientManager,
			'searchForm' => $form,
		]);
	}

	public function addAction() {
		$form = new ClientForm($this->entityManager);

		if ($this->getRequest()->isPost()) {

			$data = $this->params()->fromPost();

			$form->setData($data);
			if ($form->isValid()) {

				$data = $form->getData();

				$this->clientManager->addNewClient($data);

				return $this->redirect()->toRoute('clients');
			}
		}

		return new ViewModel([
			'form' => $form
		]);
	}

	public function editAction() {

		$form = new ClientForm($this->entityManager);

		$clientId = (int)$this->params()->fromRoute('id', -1);

		if ($clientId < 0) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$client = $this->entityManager->getRepository(Client::class)
			->findOneById($clientId);
		if ($client == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		if ($this->getRequest()->isPost()) {

			$data = $this->params()->fromPost();

			$form->setData($data);
			if ($form->isValid()) {

				$data = $form->getData();

				$this->clientManager->updateClient($client, $data);

				return $this->redirect()->toRoute('clients');
			}
		} else {
			$data = [
				'first_name' => $client->getFirstName(),
				'middle_name' => $client->getMiddleName(),
				'last_name' => $client->getLastName(),
				'email' => $client->getEmail(),
				'phone' => $client->getPhone(),
				'city_id' => $client->getCityId(),
				'address' => $client->getAddress(),
				'mbps' => $client->getMbps(),
				'monthly_price' => $client->getMonthlyPrice(),
				'payday' => $client->getPayday(),
				'join_date' => $client->getJoinDate(),
			];

			$form->setData($data);
		}

		return new ViewModel([
			'form' => $form,
			'client' => $client
		]);
	}

	public function deleteAction() {
		$clientId = (int)$this->params()->fromRoute('id', -1);

		if ($clientId < 0) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$client = $this->entityManager->getRepository(Client::class)
			->findOneById($clientId);
		if ($client == null) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$this->clientManager->removeClient($client);

		return $this->redirect()->toRoute('clients');

	}

}
