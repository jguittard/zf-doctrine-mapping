<?php

namespace ZF\Doctrine\Entity;
use Zend\Stdlib\ArraySerializableInterface;

/**
 * Interface EntityInterface
 *
 * @package     ZF\Doctrine\Entity
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
interface EntityInterface extends ArraySerializableInterface
{
    /**
     * Get the unique identifier
     *
     * @return string
     */
    public function getId();

    /**
     * Set the unique identifier
     *
     * @param mixed $id The unique identifier
     * @return mixed The current class
     */
    public function setId($id);
}
