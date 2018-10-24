<?php

namespace Zend\Log\Writer\Factory;

use Interop\Container\ContainerInterface;
use InvalidArgumentException;
use Zend\Log\Writer\MongoDB;

/**
 * MongoDb writer factory
 * This factory configuring with incoming $options param
 *
 * Class MongoDbFactory
 * @package Zend\Log\Writer\Factory
 */
class MongoDbFactory
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
     * - write_concern,  optional
     *
     * @return object|MongoDB
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (!isset($options['manager'])) {
            throw new InvalidArgumentException("Missing 'manager' option");
        }

        $mongo = $container->get($options['manager']);
        $database = isset($options['database']) ? $options['database'] : null;
        $collection = isset($options['collection']) ? $options['collection'] : null;
        $writeConcern = isset($options['write_concern']) ? $options['write_concern'] : null;

        return new MongoDB($mongo, $database, $collection, $writeConcern);
    }
}
