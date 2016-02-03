<?php
declare(strict_types=1);

namespace AppBundle\Repository;

use AppBundle\Entity\User;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends AppBaseRepository
{

    const ALIAS = 'user';

    /**
     * @param string $username
     * @return User
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByUsername($username)
    {
        $qb = $this->getDefaultQueryBuilder();
        $qb->andWhere(self::ALIAS . '.username = :username');
        $qb->setParameter('username', $username);

        return $qb->getQuery()->useResultCache(true, 60 * 5)->getSingleResult();
    }
    
}
