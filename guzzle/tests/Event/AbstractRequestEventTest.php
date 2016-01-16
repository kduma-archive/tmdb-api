<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Client;
use GuzzleHttp5\Transaction;
use GuzzleHttp5\Message\Request;

/**
 * @covers GuzzleHttp5\Event\AbstractRequestEvent
 */
class AbstractRequestEventTest extends \PHPUnit_Framework_TestCase
{
    public function testHasTransactionMethods()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $e = $this->getMockBuilder('GuzzleHttp5\Event\AbstractRequestEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $this->assertSame($t->client, $e->getClient());
        $this->assertSame($t->request, $e->getRequest());
    }

    public function testHasTransaction()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $e = $this->getMockBuilder('GuzzleHttp5\Event\AbstractRequestEvent')
            ->setConstructorArgs([$t])
            ->getMockForAbstractClass();
        $r = new \ReflectionMethod($e, 'getTransaction');
        $r->setAccessible(true);
        $this->assertSame($t, $r->invoke($e));
    }
}
