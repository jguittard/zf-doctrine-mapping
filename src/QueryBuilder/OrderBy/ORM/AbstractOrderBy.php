<?php

namespace ZF\Doctrine\QueryBuilder\OrderBy\ORM;

use Doctrine\ORM\QueryBuilder;
use ZF\Doctrine\QueryBuilder\OrderBy\Service\ORMOrderByManager;
use ZF\Doctrine\QueryBuilder\OrderBy\OrderByInterface;

/**
 * Class AbstractOrderBy
 *
 * @package     ZF\Doctrine\QueryBuilder\OrderBy\ORM
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
abstract class AbstractOrderBy implements OrderByInterface
{
    /**
     * @var ORMOrderByManager
     */
    protected $orderByManager;

    /**
     * AbstractOrderBy constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->orderByManager = $params[0];
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $options
     * @return mixed
     */
    abstract public function orderBy(QueryBuilder $queryBuilder, array $options);

}