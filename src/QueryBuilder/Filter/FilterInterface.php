<?php

namespace ZF\Doctrine\QueryBuilder\Filter;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface FilterInterface
 *
 * @package     ZF\Doctrine\QueryBuilder\Filter
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface FilterInterface
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param ClassMetadataInfo $metadata
     * @param array $option
     * @return mixed
     */
    public function filter(QueryBuilder $queryBuilder, ClassMetadataInfo $metadata, array $option);
}