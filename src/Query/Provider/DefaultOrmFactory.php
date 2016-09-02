<?php

namespace ZF\Doctrine\Query\Provider;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

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
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var ContainerInterface $container */
        $services = $container->getServiceLocator();
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
