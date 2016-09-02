<?php

namespace ZF\Doctrine\Mapper;

use InvalidArgumentException;
use ReflectionClass;
use Traversable;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;
use Zend\EventManager\ResponseCollection;
use Zend\Hydrator\HydratorAwareInterface;
use Zend\Hydrator\HydratorAwareTrait;
use ZF\Doctrine\Entity\Collection;
use ZF\Doctrine\Entity\EntityInterface;
use ZF\Doctrine\Event\DoctrineEvent;
use ZF\Doctrine\Query\Provider\QueryProviderInterface;

/**
 * Class DoctrineMapper
 *
 * @package     ZF\Doctrine\Mapper
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class DoctrineMapper implements EventManagerAwareInterface, DoctrineMapperInterface, HydratorAwareInterface, ObjectManagerAwareInterface
{
    use EventManagerAwareTrait;
    use HydratorAwareTrait;
    use ProvidesObjectManager;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var string
     */
    protected $collectionClass;

    /**
     * @var QueryProviderInterface[]|array
     */
    protected $queryProviders;

    /**
     * Get the entityClass
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     * @return DoctrineMapper
     */
    public function setEntityClass(string $entityClass): DoctrineMapper
    {
        $this->entityClass = $entityClass;
        return $this;
    }

    /**
     * Get the collectionClass
     *
     * @return string
     */
    public function getCollectionClass(): string
    {
        return $this->collectionClass;
    }

    /**
     * Set the collectionClass
     *
     * @param string $collectionClass
     * @return DoctrineMapper
     */
    public function setCollectionClass(string $collectionClass): DoctrineMapper
    {
        $this->collectionClass = $collectionClass;
        return $this;
    }

    /**
     * Get the queryProviders
     *
     * @return \ZF\Doctrine\Query\Provider\QueryProviderInterface[]
     */
    public function getQueryProviders()
    {
        return $this->queryProviders;
    }

    /**
     * @param \ZF\Doctrine\Query\Provider\QueryProviderInterface[] $queryProviders
     * @return DoctrineMapper
     */
    public function setQueryProviders($queryProviders): DoctrineMapper
    {
        // @codeCoverageIgnoreStart
        if (!is_array($queryProviders) && !$queryProviders instanceof Traversable) {
            throw new InvalidArgumentException('queryProviders must either be array or Traversable object');
        }
        foreach ($queryProviders as $qp) {
            if (!$qp instanceof QueryProviderInterface) {
                throw new InvalidArgumentException('queryProviders must implement QueryProviderInterface');
            }
        }
        // @codeCoverageIgnoreEnd
        $this->queryProviders = (array) $queryProviders;
        return $this;
    }

    /**
     * @param $method
     * @return QueryProviderInterface
     */
    public function getQueryProvider($method)
    {
        $queryProviders = $this->getQueryProviders();
        if (isset($queryProviders[$method])) {
            return $queryProviders[$method];
        }
        return $queryProviders['default'];
    }

    /**
     * @param mixed $data
     * @return EntityInterface
     */
    public function create($data): EntityInterface
    {
        if ($data instanceof EntityInterface) {
            $data = $data->getArrayCopy();
        } elseif (!is_array($data)) {
            throw new InvalidArgumentException('data must either be an array, a Traversable object or an instance of EntityInterface');
        }

        $entityClass = $this->getEntityClass();
        /** @var EntityInterface $entity */
        $entity = new $entityClass;

        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_CREATE_PRE, $entity, $data);
        $hydrator = $this->getHydrator();
        $hydrator->hydrate((array)$data, $entity);
        $this->getObjectManager()->persist($entity);

        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_CREATE_POST, $entity, $data);
        $this->getObjectManager()->flush();

        return $entity;
    }

    /**
     * @param string $id
     * @param mixed $data
     * @return EntityInterface
     */
    public function save(string $id, $data): EntityInterface
    {
        if ($data instanceof EntityInterface) {
            $data = $data->getArrayCopy();
        } elseif (!is_array($data)) {
            throw new InvalidArgumentException('data must either be an array, a Traversable object or an instance of EntityInterface');
        }
        /** @var EntityInterface $entity */
        $entity = $this->fetchOne($id);

        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_UPDATE_PRE, $entity, $data);
        $hydrator = $this->getHydrator();
        $hydrator->hydrate((array)$data, $entity);
        $this->getObjectManager()->persist($entity);

        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_UPDATE_POST, $entity, $data);
        $this->getObjectManager()->flush();

        return $entity;
    }


    /**
     * @param string $id
     * @return EntityInterface
     */
    public function fetchOne(string $id): EntityInterface
    {
        $event = new DoctrineEvent(DoctrineEvent::EVENT_FETCH_PRE, $this);
        $event->setEntityId($id)
              ->setEntityClassName($this->getEntityClass())
              ->setObjectManager($this->getObjectManager());
        $er = $this->getObjectManager()->getRepository($this->getEntityClass());
        /** @var EntityInterface $entity */
        $entity = $er->find($id);
        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_FETCH_POST, $entity);
        return $entity;
    }

    /**
     * @param array $params
     * @return EntityInterface[]|Collection
     */
    public function fetchAll(array $params = []): Collection
    {
        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_FETCH_ALL_PRE, $this->getEntityClass(), $params);
        $queryProvider = $this->getQueryProvider('fetch_all');
        $queryBuilder = $queryProvider->createQuery($this->getEntityClass(), $params);

        $adapter = $queryProvider->getPaginatedQuery($queryBuilder);
        $reflection = new ReflectionClass($this->getCollectionClass());
        /** @var Collection $collection */
        $collection = $reflection->newInstance($adapter);

        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_FETCH_ALL_POST, $this->getEntityClass(), $params);

        return $collection;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $entity = $this->fetchOne($id);
        if (!$entity) {
            return false;
        }
        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_DELETE_PRE, $entity, compact('id'));
        $this->getObjectManager()->remove($entity);
        $this->triggerDoctrineEvent(DoctrineEvent::EVENT_DELETE_POST, $entity, compact('id'));

        $this->getObjectManager()->flush();
        return true;
    }

    /**
     * @param $name
     * @param EntityInterface|string $entity
     * @param array|null $data
     * @return \Zend\EventManager\ResponseCollection
     */
    private function triggerDoctrineEvent($name, $entity, array $data = null): ResponseCollection
    {
        $event = new DoctrineEvent($name, $this);
        $event->setData($data)
              ->setObjectManager($this->getObjectManager());

        if ($entity instanceof EntityInterface) {
            $event->setEntity($entity);
        } else if (is_string($entity)) {
            $event->setEntityClassName($entity);
        }

        return $this->getEventManager()->trigger($event);
    }
}