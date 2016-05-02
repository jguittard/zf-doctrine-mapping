<?php

namespace ZF\Doctrine\Query\Provider;

use Doctrine\ORM\QueryBuilder;

/**
 * Class DefaultOrm
 *
 * @package     ZF\Doctrine\Query\Provider
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class DefaultOrm extends AbstractQueryProvider
{
    /**
     * @param string $entityClass
     * @param array $parameters
     * @return QueryBuilder
     */
    public function createQuery($entityClass, array $parameters)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getObjectManager()->createQueryBuilder();
        $queryBuilder->select('row')
                     ->from($entityClass, 'row');

        if (isset($parameters[$this->filterKey])) {
            $metadata = $this->getObjectManager()->getClassMetadata($entityClass);
            $this->filterManager->filter(
                $queryBuilder,
                $metadata,
                $parameters[$this->filterKey]
            );
        }

        if (isset($parameters[$this->orderByKey])) {
            $metadata = $this->getObjectManager()->getClassMetadata($entityClass);
            $this->orderByManager->orderBy(
                $queryBuilder,
                $metadata,
                $parameters[$this->orderByKey]
            );
        }

        return $queryBuilder;
    }
}
