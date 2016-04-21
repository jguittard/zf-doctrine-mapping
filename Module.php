<?php

namespace ZF\Doctrine;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\ModuleManager;

/**
 * Class Module
 *
 * @package     ZF\Doctrine
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface, DependencyIndicatorInterface
{
    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Expected to return an array of modules on which the current one depends on
     *
     * @return array
     */
    public function getModuleDependencies()
    {
        return [
            'DoctrineModule',
        ];
    }

    /**
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $serviceManager  = $moduleManager->getEvent()->getParam('ServiceManager');

        /** @var \Zend\ModuleManager\Listener\ServiceListenerInterface $serviceListener */
        $serviceListener = $serviceManager->get('ServiceListener');

        $serviceListener->addServiceManager(
            'ZfDoctrineQueryBuilderFilterManagerOrm',
            'zf-doctrine-querybuilder-filter-orm',
            'ZF\Doctrine\QueryBuilder\Filter\FilterInterface',
            'getDoctrineQueryBuilderFilterOrmConfig'
        );
        /*$serviceListener->addServiceManager(
            'ZfDoctrineQueryBuilderFilterManagerOdm',
            'zf-doctrine-querybuilder-filter-odm',
            'ZF\Doctrine\QueryBuilder\Filter\FilterInterface',
            'getDoctrineQueryBuilderFilterOdmConfig'
        );*/
        $serviceListener->addServiceManager(
            'ZfDoctrineQueryBuilderOrderByManagerOrm',
            'zf-doctrine-querybuilder-orderby-orm',
            'ZF\Doctrine\QueryBuilder\OrderBy\OrderByInterface',
            'getDoctrineQueryBuilderOrderByOrmConfig'
        );
        /*$serviceListener->addServiceManager(
            'ZfDoctrineQueryBuilderOrderByManagerOdm',
            'zf-doctrine-querybuilder-orderby-odm',
            'ZF\Doctrine\QueryBuilder\OrderBy\OrderByInterface',
            'getDoctrineQueryBuilderOrderByOdmConfig'
        );*/
        $serviceListener->addServiceManager(
            'ZfDoctrineQueryProviderManager',
            'zf-doctrine-query-provider',
            'ZF\Doctrine\Query\Provider\QueryProviderInterface',
            'getZfDoctrineQueryProviderConfig'
        );
        /*$serviceListener->addServiceManager(
            'ZfDoctrineQueryCreateFilterManager',
            'zf-doctrine-query-create-filter',
            'ZF\Doctrine\Server\Query\CreateFilter\QueryCreateFilterInterface',
            'getZfDoctrineQueryCreateFilterConfig'
        );*/
    }
}