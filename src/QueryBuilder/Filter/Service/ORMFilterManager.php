<?php

namespace ZF\Doctrine\QueryBuilder\Filter\Service;

use Doctrine\ORM\QueryBuilder;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use ZF\Doctrine\QueryBuilder\Filter\FilterInterface;

/**
 * Class ORMFilterManager
 *
 * @package     ZF\Doctrine\QueryBuilder\Filter\Service
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class ORMFilterManager extends AbstractPluginManager
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param $metadata
     * @param $filters
     */
    public function filter(QueryBuilder $queryBuilder, $metadata, $filters)
    {
        foreach ($filters as $option) {
            if (!isset($option['type']) or !$option['type']) {
                // @codeCoverageIgnoreStart
                throw new Exception\RuntimeException('Array element "type" is required for all filters');
            }
            // @codeCoverageIgnoreEnd
            $filter = $this->get(strtolower($option['type']), [$this]);
            $filter->filter($queryBuilder, $metadata, $option);
        }
    }

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
        if ($plugin instanceof FilterInterface) {
            // we're okay
            return;
        }
        // @codeCoverageIgnoreStart
        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\Plugin\PluginInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
        // @codeCoverageIgnoreEnd
    }

}