<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Client\Entity\Client;
use Order\Entity\Order;
use Order\Service\OrderManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController {

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

	public function __construct($entityManager, OrderManager $orderManager) {
		$this->entityManager = $entityManager;
		$this->orderManager = $orderManager;
	}

	public function indexAction() {
		return new ViewModel();
	}

	public function addOrdersAction() {

		$day = date('d');
		$clients = $this->entityManager->getRepository(Client::class)->getClientsByPayDay($day);

		if(empty($clients)) return;

		foreach ($clients as $client) {
			$data = [
				'client_id' => $client->getId(),
				'city_id' => $client->getCityId()->getId(),
				'mbps' => $client->getMbps(),
				'price' => $client->getMonthlyPrice(),
				'is_pay' => Order::IS_NOT_PAY,
				'payment_method' => Order::PAYMENT_METHOD_CASH,
				'paid_at' => '',
				'note'=> '',
			];
			$this->orderManager->addNewOrder($data);
		}

		return new ViewModel();
	}
}
