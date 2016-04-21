<?php

namespace ZF\Doctrine\Mapper;

/**
 * Class DoctrineMapperAwareTrait
 *
 * @package     ZF\Doctrine\Mapper
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
trait DoctrineMapperAwareTrait
{
    /**
     * @var DoctrineMapperInterface
     */
    protected $mapper;

    /**
     * Get the mapper
     *
     * @return DoctrineMapperInterface
     */
    public function getMapper()
    {
        return $this->mapper;
    }

    /**
     * @param DoctrineMapperInterface $mapper
     * @return DoctrineMapperAwareTrait
     */
    public function setMapper(DoctrineMapperInterface $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }
}
