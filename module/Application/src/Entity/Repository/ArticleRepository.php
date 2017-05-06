<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\Article;

class ArticleRepository extends EntityRepository
{
    public function getArticlesQueryBuilder($consideringIsPublic = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'AS a');
        if ($consideringIsPublic) {
            $qb->where('a.isPublic = 1');
        }
        $qb->orderBy('a.id', 'DESC');

        return $qb ? $qb : false;
    }

    public function getArticlesQueryBuilderForCategory($categoryId, $consideringIsPublic = false)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('a');
        $qb->from(Article::class, 'AS a');
        $qb->where('a.category = ?1');
        if ($consideringIsPublic) {
            $qb->andWhere('a.isPublic = 1');
        }
        $qb->orderBy('a.id', 'DESC');
        $qb->setParameter(1, $categoryId);

        return $qb ? $qb : false;
    }

    public function searchArticle($value)
    {
        $search = '%' . $value . '%';
        $sql = "SELECT a.id, a.title FROM Application\Entity\Article AS a WHERE a.title LIKE :search";
        $query = $this->getEntityManager()->createQuery($sql)->setParameter('search', $search);
        $result = $query->getResult();

        return $result ? $result : false;
    }
}
