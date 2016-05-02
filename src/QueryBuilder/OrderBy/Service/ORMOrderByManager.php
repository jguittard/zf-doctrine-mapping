<?php

namespace ZF\Doctrine\QueryBuilder\OrderBy\Service;

use Doctrine\ORM\QueryBuilder;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception;
use ZF\Doctrine\QueryBuilder\OrderByInterface;

/**
 * Class ORMOrderByManager
 *
 * @package     ZF\Doctrine\QueryBuilder\OrderBy\Service
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
class ORMOrderByManager extends AbstractPluginManager
{
    /**
     * @param QueryBuilder $queryBuilder
     * @param mixed $metadata
     * @param array $options
     * @return void
     */
    public function orderBy(QueryBuilder $queryBuilder, $metadata, $options)
    {
        foreach ($options as $option) {
            if (!isset($option['type']) || false == $option['type']) {
                // @codeCoverageIgnoreStart
                throw new Exception\RuntimeException('Array element "type" is required for all orderBy directives');
            }
            // @codeCoverageIgnoreEnd

            /** @var OrderByInterface $orderByHandler */
            $orderByHandler = $this->get(strtolower($option['type']), [$this]);
            $orderByHandler->orderBy($queryBuilder, $options);
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
        if ($plugin instanceof OrderByInterface) {
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