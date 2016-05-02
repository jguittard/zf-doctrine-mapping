<?php

namespace ZF\Doctrine\Query\Provider;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DefaultOrmFactory
 *
 * @package     ZF\Doctrine\Query\Provider
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class DefaultOrmFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $services */
        $services = $serviceLocator->getServiceLocator();
        $config = $services->get('Config');
        $orderByKey = isset($config['zf-doctrine-querybuilder-options']['order_by_key']) ?
            $config['zf-doctrine-querybuilder-options']['order_by_key'] : 'order-by';

        $orderByManager = $services->get('ZfDoctrineQueryBuilderOrderByManagerOrm');
        $filterManager = $services->get('ZfDoctrineQueryBuilderFilterManagerOrm');

        $filterKey = isset($config['zf-doctrine-querybuilder-options']['filter_key']) ?
            $config['zf-doctrine-querybuilder-options']['filter_key'] : 'filter';

        $qp = new DefaultOrm();
        $qp->setOrderByManager($orderByManager);
        $qp->setFilterManager($filterManager);
        $qp->setFilterKey($filterKey);
        $qp->setOrderByKey($orderByKey);

        return $qp;
    }

}