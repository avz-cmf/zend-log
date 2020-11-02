<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Log;

use Psr\Log\LogLevel;
use Psr\Log\Test\LoggerInterfaceTest;
use Zend\Log\Logger;
use Zend\Log\PsrLoggerAdapter;
use Zend\Log\Writer\Mock as MockWriter;

/**
 * @coversDefaultClass PsrLoggerAdapter
 * @covers ::<!public>
 */
class PsrLoggerAdapterTest extends LoggerInterfaceTest
{

    /**
     * @var array
     */
    protected $psrPriorityMap = [
        LogLevel::EMERGENCY,
        LogLevel::ALERT,
        LogLevel::CRITICAL,
        LogLevel::ERROR,
        LogLevel::WARNING,
        LogLevel::NOTICE,
        LogLevel::INFO,
        LogLevel::DEBUG,
    ];

    /**
     * Provides logger for LoggerInterface compat tests
     *
     * @return PsrLoggerAdapter
     */
    public function getLogger()
    {
        $this->mockWriter = new MockWriter;
        $logger = new Logger;
        $logger->addProcessor('psrplaceholder');
        $logger->addWriter($this->mockWriter);
        return new PsrLoggerAdapter($logger);
    }

    /**
     * This must return the log messages in order.
     *
     * The simple formatting of the messages is: "<LOG LEVEL> <MESSAGE>".
     *
     * Example ->error('Foo') would yield "error Foo".
     *
     * @return string[]
     */
    public function getLogs()
    {
        return array_map(function ($event) {
            $prefix = $event['level'];
            $message = $prefix . ' ' . $event['message'];
            return $message;
        }, $this->mockWriter->events);
    }

    public function tearDown()
    {
        unset($this->mockWriter);
    }

    /**
     *
     * @covers ::__construct
     * @covers ::getLogger
     */
    public function testSetLogger()
    {
        $logger = new Logger;

        $adapter = new PsrLoggerAdapter($logger);
        $this->assertAttributeEquals($logger, 'logger', $adapter);

        $this->assertSame($logger, $adapter->getLogger($logger));
    }

    /**
     * @covers ::log
     * @dataProvider logLevelsToPriorityProvider
     */
    public function testPsrLogLevelsMapsToPriorities($logLevel)
    {
        $message = 'foo';
        $context = ['bar' => 'baz'];

        $logger = $this->getMockBuilder(Logger::class)
                ->setMethods(['log'])
                ->getMock();
        $logger->expects($this->once())
                ->method('log')
                ->with(
                        $this->equalTo($logLevel), $this->equalTo($message), $this->equalTo($context)
        );

        $adapter = new PsrLoggerAdapter($logger);
        $adapter->log($logLevel, $message, $context);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function logLevelsToPriorityProvider()
    {
        $return = [];
        foreach ($this->psrPriorityMap as $level) {
            $return[] = [$level];
        }
        return $return;
    }

}
