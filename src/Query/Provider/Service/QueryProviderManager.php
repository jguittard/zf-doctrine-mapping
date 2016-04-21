<?php

namespace ZF\Doctrine\Query\Provider\Service;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use ZF\Doctrine\Query\Provider\QueryProviderInterface;

/**
 * Class QueryProviderManager
 *
 * @package     ZF\Doctrine\Query\Provider\Service
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class QueryProviderManager extends AbstractPluginManager
{
    /**
     * Validate the plugin
     *
     * Checks that the filter loaded is either a valid callback or an instance
     * of FilterInterface.
     *
     * @param  mixed $plugin
     * @return void
     * @throws Exception\RuntimeException if invalid
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof QueryProviderInterface) {
            return;
        }
        // @codeCoverageIgnoreStart
        throw new Exception\RuntimeException(
            sprintf(
                'Plugin of type %s is invalid; must implement QueryProviderInterface',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin))
            )
        );
        // @codeCoverageIgnoreEnd
    }

}