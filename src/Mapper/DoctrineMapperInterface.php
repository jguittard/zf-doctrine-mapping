<?php

namespace ZF\Doctrine\Mapper;

use ZF\Doctrine\Entity\Collection;
use ZF\Doctrine\Entity\EntityInterface;

/**
 * Interface DoctrineMapperInterface
 *
 * @package     ZF\Doctrine\Mapper
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface DoctrineMapperInterface
{
    /**
     * @param mixed $data
     * @return EntityInterface
     */
    public function create($data);

    /**
     * @param string $id
     * @param mixed $data
     * @return EntityInterface
     */
    public function save(string $id, $data): EntityInterface;

    /**
     * @param mixed $id
     * @return EntityInterface
     */
    public function fetchOne(string $id): EntityInterface;

    /**
     * @param array $params
     * @return EntityInterface[]|Collection
     */
    public function fetchAll(array $params = []): Collection;

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete(string $id): bool;
}