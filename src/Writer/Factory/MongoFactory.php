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

        $options['mongo'] = $container->get($options['mongo']);
        $options = $this->populateOptions($options, $container, 'filter_manager', 'LogFilterManager');
        $options = $this->populateOptions($options, $container, 'formatter_manager', 'LogFormatterManager');

        return new Mongo($options);
    }

    /**
     * Populates the options array with the correct container value.
     *
     * @param array $options
     * @param ContainerInterface $container
     * @param string $name
     * @param string $defaultService
     * @return array
     */
    private function populateOptions(array $options, ContainerInterface $container, $name, $defaultService)
    {
        if (isset($options[$name]) && is_string($options[$name])) {
            $options[$name] = $container->get($options[$name]);
            return $options;
        }

        if (! isset($options[$name]) && $container->has($defaultService)) {
            $options[$name] = $container->get($defaultService);
            return $options;
        }

        return $options;
    }
}
