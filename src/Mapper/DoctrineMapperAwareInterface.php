<?php

namespace ZF\Doctrine\Mapper;

/**
 * Interface DoctrineMapperAwareInterface
 *
 * @package     ZF\Doctrine\Mapper
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface DoctrineMapperAwareInterface
{
    /**
     * @param DoctrineMapperInterface $mapperInterface
     * @return mixed
     */
    public function setMapper(DoctrineMapperInterface $mapperInterface);
}