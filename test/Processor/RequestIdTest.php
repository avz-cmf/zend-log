<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ZendTest\Log\Processor;

use PHPUnit\Framework\TestCase;
use Zend\Log\Processor\RequestId;
use Psr\Log\LogLevel;

class RequestIdTest extends TestCase
{

    public function testProcess()
    {
        $processor = new RequestId();

        $event = [
            'timestamp' => '',
            'priority' => 1,
            'level' => LogLevel::ALERT,
            'message' => 'foo',
            'context' => [],
        ];

        $eventA = $processor->process($event);
        $this->assertArrayHasKey('requestId', $eventA['context']);

        $eventB = $processor->process($event);
        $this->assertArrayHasKey('requestId', $eventB['context']);

        $this->assertEquals($eventA['context']['requestId'], $eventB['context']['requestId']);
    }

    public function testProcessDoesNotOverwriteExistingRequestId()
    {
        $processor = new RequestId();

        $requestId = 'bar';

        $event = [
            'timestamp' => '',
            'priority' => 1,
            'level' => LogLevel::ALERT,
            'message' => 'foo',
            'context' => [
                'requestId' => $requestId,
            ],
        ];

        $processedEvent = $processor->process($event);

        $this->assertArrayHasKey('requestId', $processedEvent['context']);
        $this->assertSame($requestId, $processedEvent['context']['requestId']);
    }

}
