<?php
namespace Order\Controller;

use Client\Entity\Client;
use Order\Entity\Order;
use Order\Form\OrderForm;
use Order\Form\SearchForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PageAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;

class OrderController extends AbstractActionController {
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    public $entityManager;
    /**
     * Order manager.
     * @var Order\Service\OrderManager
     */
    private $orderManager;

    public function __construct($entityManager, $orderManager) {
        $this->entityManager = $entityManager;
        $this->orderManager = $orderManager;
    }

    public function indexAction() {
        $form = new SearchForm($this->entityManager);
        $sessionSearchOrderFormData = $this->orderManager->getSearchDataFromSession();

		$isOpenSearchForm = false;
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            if (isset($data['searchSubmit']) && !empty($data['searchSubmit'])) {
                $this->orderManager->addSearchDataInSession($data);
            } elseif (isset($data['clearSubmit']) && !empty($data['clearSubmit'])) {
                $this->orderManager->clearSearchDataInSession();
            }
            return $this->redirect()->toRoute('orders');
        }

        $page = (int)$this->params()->fromQuery('page', 1);
        $page = ($page < 1) ? 1 : $page;

        if (!empty($sessionSearchOrderFormData)) {
            $form->setData($sessionSearchOrderFormData);
            $orders = $this->entityManager->getRepository(Order::class)->findOrderBySearchData($sessionSearchOrderFormData);
			$isOpenSearchForm = true;
        } else {
            $orders = $this->entityManager->getRepository(Order::class)->getOrderOrderByPaidAt();
        }

        $paginator = new ZendPaginator(new PageAdapter(new ORMPaginator($orders)));
        $paginator->setCurrentPageNumber($page)->setItemCountPerPage(20);

        return new ViewModel([
        	'paginator' => $paginator,
			'orderManager' => $this->orderManager,
			'searchForm' => $form,
			'isOpenSearchForm' => $isOpenSearchForm,
		]);
    }

    public function addAction() {
        $form = new OrderForm($this->entityManager);
        $clientId = (int)$this->params()->fromQuery('client_id', 0);

        if ($clientId > 0) {
            $client = $this->entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
            $data = ['client_id' => $client->getId(), 'city_id' => $client->getCityId()->getId(), 'mbps' => $client->getMbps(), 'price' => $client->getMonthlyPrice(), 'is_pay' => Order::IS_PAY, 'payment_method' => Order::PAYMENT_METHOD_CASH, 'paid_at' => date('Y-m-d H:i:m'),];
            $form->setData($data);
        }

		$res = [];
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $res = $this->orderManager->addNewOrder($data);

				if(empty($res)) return $this->redirect()->toRoute('orders');
            }
        }
        return new ViewModel([
        	'form' => $form,
			'errors' => $res,
		]);
    }

    public function editAction() {
        $form = new OrderForm($this->entityManager);
        $orderId = (int)$this->params()->fromRoute('id', -1);
        if ($orderId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $order = $this->entityManager->getRepository(Order::class)->findOneById($orderId);
        if ($order == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }

		$res = [];
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
			$data['client_id'] = $order->getClientId()->getId();
//			echo '<pre>' . print_r($data, true) . '</pre>';
//			die();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
				$res = $this->orderManager->updateOrder($order, $data);

				if(empty($res)) return $this->redirect()->toRoute('orders');
            }
        } else {
            $data = ['client_id' => $order->getClientId(), 'city_id' => $order->getCityId(), 'mbps' => $order->getMbps(), 'price' => $order->getPrice(), 'is_pay' => $order->getIsPay(), 'payment_method' => $order->getPaymentMethod(), 'paid_at' => $order->getPaidAt(), 'note' => $order->getNote(),];
            $form->setData($data);
        }
        return new ViewModel([
        	'form' => $form,
			'order' => $order,
			'errors' => $res,
		]);
    }

    public function deleteAction() {
        $orderId = (int)$this->params()->fromRoute('id', -1);
        if ($orderId < 0) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $order = $this->entityManager->getRepository(Order::class)->findOneById($orderId);
        if ($order == null) {
            $this->getResponse()->setStatusCode(404);
            return;
        }
        $this->orderManager->removeOrder($order);
        return $this->redirect()->toRoute('orders');
    }

	public function addPeriodOrderAction() {

		$form = new OrderForm($this->entityManager, 'multiple');
		$clientId = (int)$this->params()->fromQuery('client_id', 0);
		if ($clientId > 0) {
			$client = $this->entityManager->getRepository(Client::class)->findOneBy(['id' => $clientId]);
			$data = ['client_id' => $client->getId(), 'city_id' => $client->getCityId()->getId(), 'mbps' => $client->getMbps(), 'price' => $client->getMonthlyPrice(), 'is_pay' => Order::IS_PAY, 'payment_method' => Order::PAYMENT_METHOD_CASH, 'paid_at' => date('Y-m-d H:i:m'),];
			$form->setData($data);
		}

		$res = [];
		if ($this->getRequest()->isPost()) {
			$data = $this->params()->fromPost();
			$form->setData($data);
			if ($form->isValid()) {
				$data = $form->getData();
				$res = $this->orderManager->addPeriodOrder($data);

				if(empty($res)) return $this->redirect()->toRoute('orders');
			}
		}
		return new ViewModel([
			'form' => $form,
			'errors' => $res,
		]);
	}



	public function historyAction() {
		$clientId = (int)$this->params()->fromRoute('id', -1);

		if ($clientId < 0) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$client = $this->entityManager->getRepository(Client::class)->findOneBy(['id'=>$clientId]);

		$orders = $this->entityManager->getRepository(Order::class)->getClientOrderHistory($clientId);

		$paginator = new ZendPaginator(new PageAdapter(new ORMPaginator($orders)));

		$page = (int)$this->params()->fromQuery('page', 1);
		$page = ($page < 1) ? 1 : $page;

		$paginator->setCurrentPageNumber($page)->setItemCountPerPage(12);

		return new ViewModel([
			'paginator' => $paginator,
			'client' => $client,
		]);

	}
}