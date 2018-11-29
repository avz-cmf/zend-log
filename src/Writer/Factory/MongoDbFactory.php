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

        $options['manager'] = $container->get($options['manager']);
        $options = $this->populateOptions($options, $container, 'filter_manager', 'LogFilterManager');
        $options = $this->populateOptions($options, $container, 'formatter_manager', 'LogFormatterManager');

        return new MongoDB($options);
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
