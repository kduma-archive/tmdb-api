<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Transaction;
use GuzzleHttp5\Client;
use GuzzleHttp5\Event\ErrorEvent;
use GuzzleHttp5\Exception\RequestException;
use GuzzleHttp5\Message\Request;

/**
 * @covers GuzzleHttp5\Event\ErrorEvent
 */
class ErrorEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $except = new RequestException('foo', $t->request);
        $t->exception = $except;
        $e = new ErrorEvent($t);
        $this->assertSame($e->getException(), $t->exception);
    }
}
