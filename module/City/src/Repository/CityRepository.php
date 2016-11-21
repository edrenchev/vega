<?php
namespace City\Repository;

use Doctrine\ORM\EntityRepository;
use City\Entity\City;

class CityRepository extends EntityRepository {

    public function findCityBySearchData($data) {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder();

        $queryBuilder->select('c')
            ->from(City::class, 'c')
            ->where('1 = 1');

        if (!empty($data['name'])) {
            $queryBuilder->andWhere('c.name = :name');
            $queryBuilder->setParameter('name', $data['name']);
        }

        $cities = $queryBuilder->getQuery();

        return $cities;
    }

	public function getCityOrderByName() {
		$entityManager = $this->getEntityManager();

		$queryBuilder = $entityManager->createQueryBuilder();

		$queryBuilder->select('c')
			->from(City::class, 'c')
			->orderBy('c.name', 'DESC');

		$cities = $queryBuilder->getQuery();

		return $cities;
	}
}