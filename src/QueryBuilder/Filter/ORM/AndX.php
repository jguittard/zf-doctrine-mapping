<?php

namespace ZF\Doctrine\QueryBuilder\Filter\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AndX
 *
 * @package     ZF\Doctrine\QueryBuilder\Filter\ORM
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class AndX extends AbstractFilter
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param ClassMetadataInfo $metadata
     * @param array $option
     */
    public function filter(QueryBuilder $queryBuilder, ClassMetadataInfo $metadata, array $option)
    {
        if (isset($option['where'])) {
            if ($option['where'] === 'and') {
                $queryType = 'andWhere';
            } elseif ($option['where'] === 'or') {
                $queryType = 'orWhere';
            }
        }

        if (! isset($queryType)) {
            $queryType = 'andWhere';
        }

        $andX = $queryBuilder->expr()->andX();
        $em   = $queryBuilder->getEntityManager();
        $qb   = $em->createQueryBuilder();

        foreach ($option['conditions'] as $condition) {
            $filter = $this->filterManager->get(
                strtolower($condition['type']),
                [$this->filterManager]
            );
            $filter->filter($qb, $metadata, $condition);
        }

        $dqlParts = $qb->getDqlParts();
        $andX->addMultiple($dqlParts['where']->getParts());
        $queryBuilder->setParameters(
            new ArrayCollection(array_merge_recursive(
                $queryBuilder->getParameters()->toArray(),
                $qb->getParameters()->toArray()
            ))
        );

        $queryBuilder->$queryType($andX);
    }
}
