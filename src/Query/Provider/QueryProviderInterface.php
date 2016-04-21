<?php

namespace ZF\Doctrine\Query\Provider;

use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;

/**
 * Interface QueryProviderInterface
 *
 * @package     ZF\Doctrine\Query\Provider
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface QueryProviderInterface extends ObjectManagerAwareInterface
{
    /**
     * @param string $entityClass
     * @param array $parameters
     * @return QueryBuilder
     */
    public function createQuery($entityClass, array $parameters);

    /**
     * @param QueryBuilder $queryBuilder
     * @return PaginatorAdapterInterface
     */
    public function getPaginatedQuery(QueryBuilder $queryBuilder);

    /**
     * @param string $entityClass
     * @return int
     */
    public function getCollectionTotal($entityClass);
}