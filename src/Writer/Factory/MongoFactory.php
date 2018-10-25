<?php

namespace Zend\Log\Writer\Factory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\Log\Writer\Mongo;

/**
 * Mongo writer factory
 * This factory configuring with incoming $options param
 *
 * Class MongoFactory
 * @package Zend\Log\Writer\Factory
 */
class MongoFactory
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * Expected top keys are:
     * - mongo,         required (mongo client service name)
     * - database,      optional
     * - collection,    optional
     * - save_options,  optional
     *
     * @return object|Mongo
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!isset($options['mongo'])) {
            throw new InvalidArgumentException("Missing 'mongo' option");
        }

        $mongo = $container->get($options['mongo']);
        $database = isset($options['database']) ? $options['database'] : null;
        $collection = isset($options['collection']) ? $options['collection'] : null;
        $saveOptions = isset($options['save_options']) ? $options['save_options'] : null;

        return new Mongo($mongo, $database, $collection, $saveOptions);
    }
}
