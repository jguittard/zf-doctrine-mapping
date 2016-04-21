<?php

namespace ZF\Doctrine\Mapper;

use Doctrine\Common\Persistence\ObjectManager;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZF\Doctrine\Query\Provider\QueryProviderInterface;

/**
 * Class DoctrineMapperFactory
 *
 * @package     ZF\Doctrine\Mapper
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class DoctrineMapperFactory implements AbstractFactoryInterface
{
    /**
     * Cache for canCreateServiceName lookups
     *
     * @var array
     */
    protected $lookupCache = [];

    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (array_key_exists($requestedName, $this->lookupCache)) {
            return $this->lookupCache[$requestedName];
        }

        if (!$serviceLocator->has('Config')) {
            return false;
        }

        $config = $serviceLocator->get('Config');

        if (!isset($config['doctrine-mappers']) || !is_array($config['doctrine-mappers']) || !isset($config['doctrine-mappers'][$requestedName])) {
            $this->lookupCache[$requestedName] = false;
            return false;
        }

        $config = $config['doctrine-mappers'][$requestedName];

        $className = isset($config['class']) ? $config['class'] : $requestedName;
        $className = $this->normalizeClassname($className);
        $reflection = new \ReflectionClass($className);
        if (!$reflection->isSubclassOf('ZF\Doctrine\Mapper\DoctrineMapper')) {
            // @codeCoverageIgnoreStart
            throw new ServiceNotFoundException(
                sprintf(
                    '%s requires that a valid DoctrineMapper "class" is specified for mapper %s; no service found',
                    __METHOD__,
                    $requestedName
                )
            );
        }
        // @codeCoverageIgnoreEnd

        if (!isset($config['object_manager'])) {
            // @codeCoverageIgnoreStart
            throw new ServiceNotFoundException(
                sprintf(
                    '%s requires that a valid "object_manager" is specified for mapper %s; no service found',
                    __METHOD__,
                    $requestedName
                )
            );
        }
        // @codeCoverageIgnoreEnd

        $this->lookupCache[$requestedName] = true;

        return true;
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return DoctrineMapper
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->get('Config');
        $doctrineMapperConfig = $config['doctrine-mappers'][$requestedName];

        $className = isset($doctrineMapperConfig['class']) ? $doctrineMapperConfig['class'] : $requestedName;
        $className = $this->normalizeClassname($className);

        $objectManager = $this->loadObjectManager($serviceLocator, $doctrineMapperConfig);

        $hydrator = $this->loadHydrator($serviceLocator, $doctrineMapperConfig);

        $entityClass = $doctrineMapperConfig['entity_class'];
        $collectionClass = isset($doctrineMapperConfig['collection_class']) ? $doctrineMapperConfig['collection_class'] : 'ZF\Doctrine\Entity\Collection';

        /** @var DoctrineMapper $mapper */
        $mapper = new $className;
        $mapper->setEntityClass($entityClass);
        $mapper->setObjectManager($objectManager);
        $mapper->setHydrator($hydrator);
        $mapper->setCollectionClass($collectionClass);
        $mapper->setQueryProviders($this->loadQueryProviders($serviceLocator, $objectManager));

        return $mapper;
    }

    /**
     * @param string $className
     * @return string
     */
    protected function normalizeClassname($className)
    {
        return '\\' . ltrim($className, '\\');
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $config
     * @return ObjectManager
     */
    protected function loadObjectManager(ServiceLocatorInterface $serviceLocator, $config)
    {
        if (!$serviceLocator->has($config['object_manager'])) {
            // @codeCoverageIgnoreStart
            throw new ServiceNotCreatedException('The object_manager config could not be found.');
        }
        // @codeCoverageIgnoreEnd

        return $serviceLocator->get($config['object_manager']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $mapperConfig
     * @return HydratorInterface
     */
    protected function loadHydrator(ServiceLocatorInterface $serviceLocator, $mapperConfig)
    {
        /** @var ServiceLocatorInterface $hydratorManager */
        $hydratorManager = $serviceLocator->get('HydratorManager');

        if (!$hydratorManager->has($mapperConfig['hydrator'])) {
            throw new ServiceNotCreatedException('');
        }

        return $hydratorManager->get($mapperConfig['hydrator']);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param ObjectManager $objectManager
     * @return array
     */
    protected function loadQueryProviders(ServiceLocatorInterface $serviceLocator, ObjectManager $objectManager) : array
    {
        $queryProviders = [];
        $queryManager = $serviceLocator->get('ZfDoctrineQueryProviderManager');

        if ($objectManager instanceof \Doctrine\ORM\EntityManager) {
            $queryProviders['default'] = $queryManager->get('default_orm');
        } else if ($objectManager instanceof \Doctrine\ODM\MongoDB\DocumentManager) {
            $queryProviders['default'] = $queryManager->get('default_odm');
        } else {
            throw new ServiceNotCreatedException('No valid Doctrine module found for objectManager.');
        }

        if (isset($config['query_providers'])) {
            foreach ($config['query_providers'] as $method => $plugin) {
                $queryProviders[$method] = $queryManager->get($plugin);
            }
        }

        /** @var QueryProviderInterface $provider */
        foreach ($queryProviders as $provider) {
            $provider->setObjectManager($objectManager);
        }
        return $queryProviders;
    }
}
