<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class GetCategoryParentName extends AbstractHelper
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke($parentId)
    {
        $parentId = abs((int)$parentId);
        if ($parentId === 0) {
            return 'Has no parent category';
        }

        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c.name')
           ->from(Category::class, 'As c')
           ->where('c.id = :parentId')
           ->setMaxResults(1)
           ->setParameter(':parentId', $parentId);

        $query = $qb->getQuery();
        $result = $query->getResult();

        if ($result) {
            return $result[0]['name'];
        }

        return $result ? $result[0]['name'] : 'Category name not found';
    }
}
