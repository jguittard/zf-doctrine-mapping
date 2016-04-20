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
    public function store($data);

    /**
     * @param mixed $id
     * @return EntityInterface
     */
    public function fetchOne($id);

    /**
     * @param array $params
     * @return EntityInterface[]|Collection
     */
    public function fetchAll($params = []);

    /**
     * @param mixed $id
     * @return bool
     */
    public function delete($id);
}