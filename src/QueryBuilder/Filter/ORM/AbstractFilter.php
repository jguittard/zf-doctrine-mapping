<?php

namespace ZF\Doctrine\QueryBuilder\Filter\ORM;

use \DateTime;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\QueryBuilder;
use ZF\Doctrine\QueryBuilder\Filter\FilterInterface;
use ZF\Doctrine\QueryBuilder\Filter\Service\ORMFilterManager;

/**
 * Class AbstractFilter
 *
 * @package     ZF\Doctrine\QueryBuilder\Filter\ORM
 * @version     1.0
 * @author      Julien Guittard <julien.guittard@me.com>
 * @see         http://github.com/jguittard/zf-doctrine-mapping for the canonical source repository
 * @link        https://doctrine-mapping.io Doctrine Mapping Website
 * @license     http://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @copyright   Copyright (c) 2016 Zend Technologies USA Inc. (http://www.zend.com)
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * @var ORMFilterManager
     */
    protected $filterManager;

    /**
     * AbstractFilter constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->filterManager = $params[0];
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param ClassMetadataInfo $metadata
     * @param array $option
     * @return void
     */
    abstract public function filter(QueryBuilder $queryBuilder, ClassMetadataInfo $metadata, array $option);

    /**
     * @param $metadata
     * @param $field
     * @param $value
     * @param $format
     * @param bool $doNotTypecastDatetime
     * @return mixed
     */
    protected function typeCastField(ClassMetadataInfo $metadata, $field, $value, $format, $doNotTypecastDatetime = false)
    {
        if (! isset($metadata->fieldMappings[$field])) {
            return $value;
        }

        switch ($metadata->fieldMappings[$field]['type']) {
            case 'string':
                settype($value, 'string');
                break;
            case 'integer':
            case 'smallint':
                #case 'bigint':  // Don't try to manipulate bigints?
                settype($value, 'integer');
                break;
            case 'boolean':
                settype($value, 'boolean');
                break;
            case 'decimal':
            case 'float':
                settype($value, 'float');
                break;
            case 'date':
                // For dates set time to midnight
                if ($value && ! $doNotTypecastDatetime) {
                    if (! $format) {
                        $format = 'Y-m-d';
                    }
                    $value = DateTime::createFromFormat($format, $value);
                    $value = DateTime::createFromFormat('Y-m-d H:i:s', $value->format('Y-m-d') . ' 00:00:00');
                }
                break;
            case 'time':
                if ($value && ! $doNotTypecastDatetime) {
                    if (!$format) {
                        $format = 'H:i:s';
                    }
                    $value = DateTime::createFromFormat($format, $value);
                }
                break;
            case 'datetime':
                if ($value && ! $doNotTypecastDatetime) {
                    if (! $format) {
                        $format = 'Y-m-d H:i:s';
                    }
                    $value = DateTime::createFromFormat($format, $value);
                }
                break;
            default:
                break;
        }

        return $value;
    }
}