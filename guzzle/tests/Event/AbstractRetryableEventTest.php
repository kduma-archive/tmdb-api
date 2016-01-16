<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Client;
use GuzzleHttp5\Transaction;
use GuzzleHttp5\Message\Request;

/**
 * @covers GuzzleHttp5\Event\AbstractRetryableEvent
 */
class AbstractRetryableEventTest extends \PHPUnit_Framework_TestCase
{
    public function testCanRetry()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->transferInfo = ['foo' => 'bar'];
        $e = $this->getMockBuilder('GuzzleHttp5\Event\AbstractRetryableEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $e->retry();
        $this->assertTrue($e->isPropagationStopped());
        $this->assertEquals('retry', $t->state);
    }

    public function testCanRetryAfterDelay()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->transferInfo = ['foo' => 'bar'];
        $e = $this->getMockBuilder('GuzzleHttp5\Event\AbstractRetryableEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $e->retry(10);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertEquals('retry', $t->state);
        $this->assertEquals(10, $t->request->getConfig()->get('delay'));
    }
}
