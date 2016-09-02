<?php

namespace ZF\Doctrine\Mapper;

use Doctrine\Common\Persistence\ObjectManager;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\Hydrator\HydratorInterface;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
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
    const DEFAULT_MAPPER_CLASS = 'ZF\Doctrine\Mapper\DoctrineMapper';

    /**
     * Cache for canCreateServiceName lookups
     *
     * @var array
     */
    protected $lookupCache = [];

    /**
     * Can the factory create an instance for the service?
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        if (array_key_exists($requestedName, $this->lookupCache)) {
            return $this->lookupCache[$requestedName];
        }

        if (!$container->has('Config')) {
            return false;
        }

        $config = $container->get('Config');

        if (!isset($config['doctrine-mappers']) || !is_array($config['doctrine-mappers']) || !isset($config['doctrine-mappers'][$requestedName])) {
            $this->lookupCache[$requestedName] = false;
            return false;
        }

        $config = $config['doctrine-mappers'][$requestedName];

        $className = isset($config['class']) ? $config['class'] : self::DEFAULT_MAPPER_CLASS;
        $className = $this->normalizeClassname($className);
        $reflection = new \ReflectionClass($className);
        $instance = $reflection->newInstanceWithoutConstructor();
        if (!$instance instanceof DoctrineMapper) {
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
        $config = $container->get('Config');
        $doctrineMapperConfig = $config['doctrine-mappers'][$requestedName];

        $className = isset($doctrineMapperConfig['class']) ? $doctrineMapperConfig['class'] : self::DEFAULT_MAPPER_CLASS;
        $className = $this->normalizeClassname($className);

        $objectManager = $this->loadObjectManager($container, $doctrineMapperConfig);

        $hydrator = $this->loadHydrator($container, $doctrineMapperConfig);

        $entityClass = $doctrineMapperConfig['entity_class'];
        $collectionClass = isset($doctrineMapperConfig['collection_class']) ? $doctrineMapperConfig['collection_class'] : 'ZF\Doctrine\Entity\Collection';

        /** @var DoctrineMapper $mapper */
        $mapper = new $className;
        $mapper->setEntityClass($entityClass);
        $mapper->setObjectManager($objectManager);
        $mapper->setHydrator($hydrator);
        $mapper->setCollectionClass($collectionClass);
        $mapper->setQueryProviders($this->loadQueryProviders($container, $objectManager));

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
     * @param ContainerInterface $container
     * @param $config
     * @return ObjectManager
     */
    protected function loadObjectManager(ContainerInterface $container, $config)
    {
        if (!$container->has($config['object_manager'])) {
            // @codeCoverageIgnoreStart
            throw new ServiceNotCreatedException('The object_manager config could not be found.');
        }
        // @codeCoverageIgnoreEnd

        return $container->get($config['object_manager']);
    }

    /**
     * @param ContainerInterface $container
     * @param $mapperConfig
     * @return HydratorInterface
     */
    protected function loadHydrator(ContainerInterface $container, $mapperConfig)
    {
        /** @var ContainerInterface $hydratorManager */
        $hydratorManager = $container->get('HydratorManager');

        if (!$hydratorManager->has($mapperConfig['hydrator'])) {
            throw new ServiceNotCreatedException(sprintf('Could not locate %s hydrator', $mapperConfig['hydrator']));
        }

        return $hydratorManager->get($mapperConfig['hydrator']);
    }

    /**
     * @param ContainerInterface $container
     * @param ObjectManager $objectManager
     * @return array
     */
    protected function loadQueryProviders(ContainerInterface $container, ObjectManager $objectManager) : array
    {
        $queryProviders = [];
        $queryManager = $container->get('ZfDoctrineQueryProviderManager');

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
