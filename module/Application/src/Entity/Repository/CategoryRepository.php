<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAllCategories()
    {
        $sql = "SELECT c FROM Application\Entity\Category c";
        $query = $this->getEntityManager()->createQuery($sql);
        $result = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result ? $result : false;
    }

    public function getCategoriesQueryBuilder()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('c')
            ->from('Application\Entity\Category', 'AS c')
            ->orderBy('c.id', 'ASC');

        return $qb ? $qb : false;
    }
}
