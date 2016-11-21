<?php
namespace Client\Repository;

use Doctrine\ORM\EntityRepository;
use Client\Entity\Client;

/**
 * This is the custom repository class for Post entity.
 */
class ClientRepository extends EntityRepository {

    public function findClientsBySearchData($data) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
            ->from(Client::class, 'c')
            ->where('1 = 1');

        if (!empty($data['first_name'])) {
            $queryBuilder->andWhere('c.firstName = :firstName');
            $queryBuilder->setParameter('firstName', $data['first_name']);
        }
        if (!empty($data['middle_name'])) {
            $queryBuilder->andWhere('c.middleName = :middleName');
            $queryBuilder->setParameter('middleName', $data['middle_name']);
        }
        if (!empty($data['last_name'])) {
            $queryBuilder->andWhere('c.lastName = :lastName');
            $queryBuilder->setParameter('lastName', $data['last_name']);
        }
        if (!empty($data['phone'])) {
            $queryBuilder->andWhere('c.phone = :phone');
            $queryBuilder->setParameter('phone', $data['phone']);
        }
        if (!empty($data['city_id'])) {
            $queryBuilder->andWhere('c.cityId = :cityId');
            $queryBuilder->setParameter('cityId', $data['city_id']);
        }
        if (!empty($data['status'])) {
            $queryBuilder->andWhere('c.status = :status');
            $queryBuilder->setParameter('status', $data['status']);
        }
        if (!empty($data['join_date_from'])) {
            $queryBuilder->andWhere('c.joinDate >= :joinDateFrom');
            $queryBuilder->setParameter('joinDateFrom', $data['join_date_from']);
        }
        if (!empty($data['join_date_to'])) {
            $queryBuilder->andWhere('c.joinDate < :joinDateTo');
            $queryBuilder->setParameter('joinDateTo', $data['join_date_to']);
        }

        $clients = $queryBuilder->getQuery();

        return $clients;
    }

	public function getClientsOrderByJoinDate() {
		$entityManager = $this->getEntityManager();

		$queryBuilder = $entityManager->createQueryBuilder();

		$queryBuilder->select('c')
			->from(Client::class, 'c')
			->orderBy('c.joinDate', 'DESC');

		$clients = $queryBuilder->getQuery();

		return $clients;
	}

	public function getClientsByPayDay($day) {
		$entityManager = $this->getEntityManager();

		$queryBuilder = $entityManager->createQueryBuilder();

		$queryBuilder->select('c')
			->from(Client::class, 'c')
			->andWhere('c.payday = :payDay')
			->setParameter('payDay', $day);

		$clients = $queryBuilder->getQuery()->getResult();

		return $clients;

	}
}