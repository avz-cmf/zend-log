<?php

namespace Zend\Log\Writer\Factory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\Log\Writer\Db;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Db writer factory
 * This factory configuring with incoming $options param
 *
 * Class DbFactory
 * @package Zend\Log\Writer\Factory
 */
class DbFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * Expected top keys are:
     * - db,         required (db adapter service name)
     * - table,      optional
     * - column,     optional
     * - separator,  optional
     *
     * @return object|Db
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!isset($options['db'])) {
            throw new InvalidArgumentException("Missing 'db' option");
        }

        $db = $container->get($options['db']);
        $tableName = isset($options['table']) ? $options['table'] : null;
        $column = isset($options['column']) ? $options['column'] : null;
        $separator = isset($options['separator']) ? $options['separator'] : null;

        return new Db($db, $tableName, $column, $separator);
    }
}
