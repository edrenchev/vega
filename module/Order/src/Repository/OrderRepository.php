<?php
namespace Order\Repository;

use Doctrine\ORM\EntityRepository;
use Order\Entity\Order;

/**
 * This is the custom repository class for Post entity.
 */
class OrderRepository extends EntityRepository {
    public function findOrderBySearchData($data) {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('o')->from(Order::class, 'o')->where('1 = 1');
        if (!empty($data['client_id'])) {
            $queryBuilder->andWhere('o.clientId = :clientId');
            $queryBuilder->setParameter('clientId', $data['client_id']);
        }
        if (!empty($data['city_id'])) {
            $queryBuilder->andWhere('o.cityId = :cityId');
            $queryBuilder->setParameter('cityId', $data['city_id']);
        }
        if (!empty($data['price'])) {
            $queryBuilder->andWhere('o.price = :price');
            $queryBuilder->setParameter('price', $data['price']);
        }
        if (!empty($data['is_pay'])) {
            $queryBuilder->andWhere('o.isPay = :isPay');
            $queryBuilder->setParameter('isPay', $data['is_pay']);
        }
        if (!empty($data['mbps'])) {
            $queryBuilder->andWhere('o.mbps = :mbps');
            $queryBuilder->setParameter('mbps', $data['mbps']);
        }
        if (!empty($data['paid_at_from'])) {
            $queryBuilder->andWhere('o.paidAt >= :paidAtFrom');
            $queryBuilder->setParameter('paidAtFrom', $data['paid_at_from']);
        }
        if (!empty($data['paid_at_to'])) {
            $queryBuilder->andWhere('o.paidAt < :paidAtTo');
            $queryBuilder->setParameter('paidAtTo', $data['paid_at_to']);
        }
        $clients = $queryBuilder->getQuery();
        return $clients;
    }

    public function getOrderOrderByPaidAt() {
        $entityManager = $this->getEntityManager();
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('o')->from(Order::class, 'o')->addOrderBy('o.isPay', 'ASC')->addOrderBy('o.paidAt', 'DESC');
		$orders = $queryBuilder->getQuery();
        return $orders;
    }

    public function isClientPay($clientId, $date) {
		$date = new \DateTime($date);
		$firstDayOfMonth = $date->modify('first day of this month')->format('Y-m-d');
		$lastDayOfMonth = $date->modify('last day of this month')->format('Y-m-d');
		$entityManager = $this->getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();
		$queryBuilder->select('o')
			->from(Order::class, 'o')
			->where('o.clientId = :clientId AND o.paidAt >= :firstDayOfMonth AND o.paidAt <= :lastDayOfMonth')
			->setParameters([
				'clientId' => $clientId,
				'firstDayOfMonth' => $firstDayOfMonth,
				'lastDayOfMonth' => $lastDayOfMonth,
			]);

		$orders = $queryBuilder->getQuery()->getResult();

		if(empty($orders)) return false;

		return true;
	}

	public function getClientOrderHistory($clientId) {
		$entityManager = $this->getEntityManager();
		$queryBuilder = $entityManager->createQueryBuilder();
		$queryBuilder->select('o')->from(Order::class, 'o')->where("o.clientId = :clientId")->orderBy('o.paidAt', 'DESC')->setParameter('clientId', $clientId);
		$orders = $queryBuilder->getQuery();
		return $orders;
	}
}