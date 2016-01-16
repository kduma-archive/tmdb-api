<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Transaction;
use GuzzleHttp5\Client;
use GuzzleHttp5\Event\BeforeEvent;
use GuzzleHttp5\Message\Request;
use GuzzleHttp5\Message\Response;

/**
 * @covers GuzzleHttp5\Event\BeforeEvent
 */
class BeforeEventTest extends \PHPUnit_Framework_TestCase
{
    public function testInterceptsWithEvent()
    {
        $t = new Transaction(new Client(), new Request('GET', '/'));
        $t->exception = new \Exception('foo');
        $e = new BeforeEvent($t);
        $response = new Response(200);
        $e->intercept($response);
        $this->assertTrue($e->isPropagationStopped());
        $this->assertSame($t->response, $response);
        $this->assertNull($t->exception);
    }
}
