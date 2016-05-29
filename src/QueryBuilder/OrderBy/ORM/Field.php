<?php

namespace ZF\Doctrine\QueryBuilder\OrderBy\ORM;

use Exception;
use Doctrine\ORM\QueryBuilder;

/**
 * Class Field
 *
 * @package     ZF\Doctrine\QueryBuilder\OrderBy\ORM
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class Field extends AbstractOrderBy
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param array $option
     * @throws Exception
     * @return void
     */
    public function orderBy(QueryBuilder $queryBuilder, array $option)
    {
        $option['alias'] = isset($option['alias']) ? $option['alias'] : 'row';
        if (!isset($option['direction']) || !in_array(strtolower($option['direction']), ['asc', 'desc'])) {
            throw new Exception('Invalid direction in orderby directive');
        }
        $queryBuilder->addOrderBy($option['alias'] . '.' . $option['field'], $option['direction']);
    }

}