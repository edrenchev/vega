<?php
namespace Order\Service;

use Client\Entity\Client;
use Order\Entity\Order;

class OrderManager {
	/**
	 * Entity manager.
	 * @var Doctrine\ORM\EntityManager;
	 */
	private $entityManager;

	/**
	 * Entity manager.
	 * @var Zend\Session\Container;
	 */
	private $sessionSearchOrderForm;

	/**
	 * Constructor.
	 */
	public function __construct($entityManager, $sessionSearchOrderForm) {
		$this->entityManager = $entityManager;
		$this->sessionSearchOrderForm = $sessionSearchOrderForm;
	}

	public function addNewOrder($data) {

		if (isset($data['paid_at'])) {

			if (($data['paid_at'] == '' || $data['paid_at'] == '0000-00-00')) {
				$date = new \DateTime();
			} else {
				$date = new \DateTime($data['paid_at']);
			}

			$isClientPay = $this->entityManager->getRepository(Order::class)->isClientPay($data['client_id'], $date->format('Y-m-d'));

			$firstDayOfMonth = $date->modify('first day of this month')->format('d.m.Y');
			$lastDayOfMonth = $date->modify('last day of this month')->format('d.m.Y');
			if ($isClientPay) {
				return ["Има въведено плащане за периода <strong>{$firstDayOfMonth} - {$lastDayOfMonth}</strong>"];
			}

		}

		// Create new Post entity.
		$order = new Order();
		$order->setClientId($this->entityManager->getRepository(\Client\Entity\Client::class)->findOneById($data['client_id']));
		$order->setCityId($data['city_id']);
		$order->setMbps($data['mbps']);
		$order->setPrice($data['price']);
		$order->setIsPay($data['is_pay']);
		$order->setPaymentMethod($data['payment_method']);
		$order->setPaidAt($data['paid_at']);
		$order->setNote($data['note']);

		$currentDate = date('Y-m-d H:i:s');
		$order->setCreatedAt($currentDate);

		$this->entityManager->persist($order);

		$this->entityManager->flush();

		return [];
	}

	public function updateOrder(Order $order, $data) {

		if (isset($data['paid_at']) && $data['paid_at'] != $order->getPaidAt()) {

			if (($data['paid_at'] == '' || $data['paid_at'] == '0000-00-00')) {
				$date = new \DateTime();
			} else {
				$date = new \DateTime($data['paid_at']);
			}

			$isClientPay = $this->entityManager->getRepository(Order::class)->isClientPay($data['client_id'], $date->format('Y-m-d'));

			$firstDayOfMonth = $date->modify('first day of this month')->format('d.m.Y');
			$lastDayOfMonth = $date->modify('last day of this month')->format('d.m.Y');
			if ($isClientPay) {
				return ["Има въведено плащане за периода <strong>{$firstDayOfMonth} - {$lastDayOfMonth}</strong>"];
			}

		}

		$order->setClientId($this->entityManager->getRepository(\Client\Entity\Client::class)->findOneById($data['client_id']));
		$order->setCityId($data['city_id']);
		$order->setMbps($data['mbps']);
		$order->setPrice($data['price']);
		$order->setIsPay($data['is_pay']);
		$order->setPaymentMethod($data['payment_method']);
		$order->setPaidAt($data['paid_at']);
		$order->setNote($data['note']);

		$currentDate = date('Y-m-d H:i:s');
		$order->setCreatedAt($currentDate);

		$this->entityManager->flush();
		return [];
	}

	public function removeOrder(Order $order) {

		$this->entityManager->remove($order);

		$this->entityManager->flush();
	}

	public function removeClient(Client $client) {

		$this->entityManager->remove($client);

		$this->entityManager->flush();
	}

	public function addSearchDataInSession($data) {
		unset($data['searchSubmit'], $data['clearSubmit']);
		$this->sessionSearchOrderForm->data = $data;
	}

	public function getSearchDataFromSession() {
		if (isset($this->sessionSearchOrderForm->data)) {
			return $this->sessionSearchOrderForm->data;
		}

		return [];
	}

	public function clearSearchDataInSession() {
		unset($this->sessionSearchOrderForm->data);
	}

	public function addPeriodOrder($data) {

		$paidAtFrom = new \DateTime($data['paid_at_from']);
		$paidAtTo = new \DateTime($data['paid_at_to']);

		if($paidAtFrom->getTimestamp() > $paidAtTo->getTimestamp()) {
			return ["<strong>Дата на плащане от</strong> трябва да е <strong>></strong> от <strong>Дата на плащане до</strong> !!!"];
		}

		if($paidAtFrom->format('m') == $paidAtTo->format('m')) {
			return ["<strong>Дата на плащане от</strong> и <strong>Дата на плащане до</strong> трябва да са различни месеци!!!"];
		}

		$client = $this->entityManager->getRepository(Client::class)->findOneBy(['id'=>$data['client_id']]);
		$payday = $client->getPayDay();

		$tmpStartDate = new \DateTime($paidAtFrom->format('Y') . '-' . $paidAtFrom->format('m') . '-' . $payday);
		$tmpEndDate = new \DateTime($paidAtTo->format('Y') . '-' . $paidAtTo->format('m') . '-' . $payday);
		$dates = [];
		do {
			$dates[] = $tmpStartDate->format('Y-m-d');
			$tmpStartDate->add(new \DateInterval('P1M'));
		}
		while ($tmpStartDate->getTimestamp() <= $tmpEndDate->getTimestamp());

		foreach ($dates as $date) {
			$isClientPay = $this->entityManager->getRepository(Order::class)->isClientPay($data['client_id'], $date);
			if ($isClientPay) {
				$tmpDate = new \DateTime($date);
				$firstDayOfMonth = $tmpDate->modify('first day of this month')->format('d.m.Y');
				$lastDayOfMonth = $tmpDate->modify('last day of this month')->format('d.m.Y');
				return ["Има въведено плащане за периода <strong>{$firstDayOfMonth} - {$lastDayOfMonth}</strong>"];
			}
		}

		foreach ($dates as $date) {
			$order = new Order();
			$order->setClientId($this->entityManager->getRepository(\Client\Entity\Client::class)->findOneById($data['client_id']));
			$order->setCityId($data['city_id']);
			$order->setMbps($data['mbps']);
			$order->setPrice($data['price']);
			$order->setIsPay($data['is_pay']);
			$order->setPaymentMethod($data['payment_method']);
			$order->setPaidAt($date);
			$order->setNote($data['note']);

			$currentDate = date('Y-m-d H:i:s');
			$order->setCreatedAt($currentDate);

			$this->entityManager->persist($order);

			$this->entityManager->flush();
		}

		return [];

	}
}