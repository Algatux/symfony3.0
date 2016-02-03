<?php
declare(strict_types=1);

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AppBaseRepository extends EntityRepository
{

    const ALIAS = 'default_table_name';

    /**
     * @return QueryBuilder
     */
    public function getDefaultQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder($this::ALIAS);
    }

}
