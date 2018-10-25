<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zend-log for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\Log;

use Zend\Log\Writer\Db;
use Zend\Log\Writer\Factory\DbFactory;
use Zend\Log\Writer\Factory\MongoDbFactory;
use Zend\Log\Writer\Factory\MongoFactory;
use Zend\Log\Writer\Mongo;
use Zend\Log\Writer\MongoDB;

class ConfigProvider
{
    /**
     * Return configuration for this component.
     *
     * @return array
     */
    public function __invoke()
    {
        return [
            'dependencies' => $this->getDependencyConfig(),
        ];
    }

    /**
     * Return dependency mappings for this component.
     *
     * @return array
     */
    public function getDependencyConfig()
    {
        return [
            'log_writers' => [
                'factories' => [
                    Db::class               => DbFactory::class,
                    'db'                    => DbFactory::class,
                    'zendlogwriterdb'       => DbFactory::class,
                    Mongo::class            => MongoFactory::class,
                    'mongo'                 => MongoFactory::class,
                    'zendlogwritermongo'    => MongoFactory::class,
                    MongoDb::class          => MongoDbFactory::class,
                    'mongodb'               => MongoDbFactory::class,
                    'zendlogwritermongodb'  => MongoDbFactory::class,
                    Logger::class           => LoggerServiceFactory::class,
                ],
            ],
            'abstract_factories' => [
                LoggerAbstractServiceFactory::class,
            ],
            'factories' => [
                'LogFilterManager'    => FilterPluginManagerFactory::class,
                'LogFormatterManager' => FormatterPluginManagerFactory::class,
                'LogProcessorManager' => ProcessorPluginManagerFactory::class,
                'LogWriterManager'    => WriterPluginManagerFactory::class,
            ],
        ];
    }
}
