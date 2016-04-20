<?php

namespace ZF\Doctrine\QueryBuilder\OrderBy\Service;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * Class ORMOrderByManagerFactory
 *
 * @package     ZF\Doctrine\QueryBuilder\Service
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class ORMOrderByManagerFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = 'ZF\Doctrine\QueryBuilder\OrderBy\Service\ORMOrderByManager';
}