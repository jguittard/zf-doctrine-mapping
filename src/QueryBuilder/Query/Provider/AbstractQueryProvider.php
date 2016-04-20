<?php

namespace ZF\Doctrine\Query\Provider;

use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\Paginator\Adapter\AdapterInterface as PaginatorAdapterInterface;
use Zend\ServiceManager\AbstractPluginManager;
use ZF\Doctrine\Paginator\Adapter\DoctrineOrmAdapter;

/**
 * Class AbstractQueryProvider
 *
 * @package     ZF\Doctrine\Query\Provider
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
abstract class AbstractQueryProvider implements ObjectManagerAwareInterface, QueryProviderInterface
{
    use ProvidesObjectManager;

    /**
     * @var AbstractPluginManager
     */
    protected $orderByManager;

    /**
     * @var string
     */
    protected $orderByKey;

    /**
     * @var AbstractPluginManager
     */
    protected $filterManager;

    /**
     * @var string
     */
    protected $filterKey;

    /**
     * @param AbstractPluginManager $orderByManager
     * @return AbstractQueryProvider
     */
    public function setOrderByManager(AbstractPluginManager $orderByManager)
    {
        $this->orderByManager = $orderByManager;
        return $this;
    }

    /**
     * Set the orderByKey
     *
     * @param string $orderByKey
     * @return AbstractQueryProvider
     */
    public function setOrderByKey($orderByKey)
    {
        $this->orderByKey = $orderByKey;
        return $this;
    }

    /**
     * @param AbstractPluginManager $filterManager
     * @return AbstractQueryProvider
     */
    public function setFilterManager(AbstractPluginManager $filterManager)
    {
        $this->filterManager = $filterManager;
        return $this;
    }

    /**
     * Set the filterKey
     *
     * @param string $filterKey
     * @return AbstractQueryProvider
     */
    public function setFilterKey($filterKey)
    {
        $this->filterKey = $filterKey;
        return $this;
    }

    /**
     * @param string $entityClass
     * @param array $parameters
     * @return QueryBuilder
     */
    abstract public function createQuery($entityClass, array $parameters);

    /**
     * @param QueryBuilder $queryBuilder
     * @return PaginatorAdapterInterface
     */
    public function getPaginatedQuery(QueryBuilder $queryBuilder)
    {
        return new DoctrineOrmAdapter($queryBuilder->getQuery(), false);
    }

    /**
     * @param string $entityClass
     * @return int
     */
    public function getCollectionTotal($entityClass)
    {
        $queryBuilder = $this->getObjectManager()->createQueryBuilder();
        $cmf = $this->getObjectManager()->getMetadataFactory();
        $entityMetaData = $cmf->getMetadataFor($entityClass);
        $identifier = $entityMetaData->getIdentifier();
        $queryBuilder->select('count(row.' . $identifier[0] . ')')
                     ->from($entityClass, 'row');
        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
