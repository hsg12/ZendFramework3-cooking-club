<?php

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Application\Entity\User;

class UserRepository extends EntityRepository
{
    public function login(User $user, $authService)
    {
        $adapter = $authService->getAdapter();
        $adapter->setIdentity($user->getName());
        $adapter->setCredential($user->getPassword());
        $authResult = $authService->authenticate();

        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();
            $authService->getStorage()->write($identity);
        }

        return $authResult;
    }

    public function getAdmins()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
           ->from('Application\Entity\User', 'AS u')
           ->where('u.role = ?1')
           ->orderBy('u.role', 'ASC')
           ->setParameter(1, 'admin');
        $query = $qb->getQuery();
        return $query->getResult() ? $query->getResult() : false;
    }

    public function getUsersQueryBuilder()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('Application\Entity\User', 'AS u')
            ->where('u.role = ?1')
            ->orderBy('u.id', 'DESC')
            ->setParameter(1, 'user');

        return $qb ? $qb : false;
    }

    public function searchUser($name)
    {
        $name = '%' . $name . '%';
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u.id, u.name')
            ->from('Application\Entity\User', 'AS u')
            ->where('u.name LIKE :name')
            ->andWhere('u.role = :user')
            ->setParameters([':name' => $name, ':user' => 'user']);

        $query = $qb->getQuery();
        $result = $query->getResult();
        return $result ? $result : false;
    }
}
