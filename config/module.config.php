<?php
/**
 * Doctrine Mapping module configuration
 *
 * @package     ZF\Doctrine
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */

namespace ZF\Doctrine;

return [
    'service_manager' => [
        'factories' => [
            'ZfDoctrineQueryBuilderFilterManagerOrm' => 'ZF\Doctrine\QueryBuilder\Filter\Service\ORMFilterManagerFactory',
            'ZfDoctrineQueryBuilderFilterManagerOdm' => 'ZF\Doctrine\QueryBuilder\Filter\Service\ODMFilterManagerFactory',
            'ZfDoctrineQueryBuilderOrderByManagerOrm' => 'ZF\Doctrine\QueryBuilder\OrderBy\Service\ORMOrderByManagerFactory',
            'ZfDoctrineQueryBuilderOrderByManagerOdm' => 'ZF\Doctrine\QueryBuilder\OrderBy\Service\ODMOrderByManagerFactory',
            'ZfDoctrineQueryProviderManager' => Query\Provider\Service\QueryProviderManagerFactory::class,
        ],
    ],
    'abstract_factories' => [
        Mapper\DoctrineMapperFactory::class,
    ],
];