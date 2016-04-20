<?php

namespace ZF\Doctrine\Event;

use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Zend\EventManager\Event;
use ZF\Doctrine\Entity\EntityInterface;

/**
 * Class DoctrineEvent
 *
 * @package     ZF\Doctrine\Event
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class DoctrineEvent extends Event
{
    const EVENT_FETCH_PRE        = 'fetch.pre';
    const EVENT_FETCH_POST       = 'fetch.post';
    const EVENT_FETCH_ALL_PRE    = 'fetch-all.pre';
    const EVENT_FETCH_ALL_POST   = 'fetch-all.post';
    const EVENT_CREATE_PRE       = 'create.pre';
    const EVENT_CREATE_POST      = 'create.post';
    const EVENT_UPDATE_PRE       = 'update.pre';
    const EVENT_UPDATE_POST      = 'update.post';
    const EVENT_DELETE_PRE       = 'delete.pre';
    const EVENT_DELETE_POST      = 'delete.post';

    use ProvidesObjectManager;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var string
     */
    protected $entityClassName;
    /**
     * @var string
     */
    protected $entityId;

    /**
     * @var array
     */
    protected $data;

    /**
     * Get the entity
     *
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the entity
     *
     * @param EntityInterface $entity
     * @return DoctrineEvent
     */
    public function setEntity(EntityInterface $entity)
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * Get the entityClassName
     *
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Set the entityClassName
     *
     * @param string $entityClassName
     * @return DoctrineEvent
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;
        return $this;
    }

    /**
     * Get the entityId
     *
     * @return string
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set the entityId
     *
     * @param string $entityId
     * @return DoctrineEvent
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
        return $this;
    }

    /**
     * Get the data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set the data
     *
     * @param array $data
     * @return DoctrineEvent
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
