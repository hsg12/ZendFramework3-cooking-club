<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class CategoryRepository extends EntityRepository
{
    public function findAllCategories(EntityManagerInterface $entityManager)
    {
        $sql = "SELECT c FROM Application\Entity\Category c";
        $query = $entityManager->createQuery($sql);
        $result = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result ? $result : false;
    }

    public function getCategoriesQueryBuilder(EntityManagerInterface $entityManager)
    {
        $qb = $entityManager->createQueryBuilder();
        $qb->select('c')
            ->from('Application\Entity\Category', 'AS c')
            ->orderBy('c.id', 'ASC');

        return $qb ? $qb : false;
    }
}
