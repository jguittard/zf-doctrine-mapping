<?php

namespace ZF\Doctrine\QueryBuilder;

use Doctrine\ORM\QueryBuilder;

/**
 * Class OrderByInterface
 *
 * @package     ZF\Doctrine\QueryBuilder
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface OrderByInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $options
     * @return mixed
     */
    public function orderBy(QueryBuilder $queryBuilder, array $options);
}