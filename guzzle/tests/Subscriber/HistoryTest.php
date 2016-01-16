<?php
namespace GuzzleHttp5\Tests\Subscriber;

use GuzzleHttp5\Transaction;
use GuzzleHttp5\Client;
use GuzzleHttp5\Event\CompleteEvent;
use GuzzleHttp5\Event\ErrorEvent;
use GuzzleHttp5\Exception\RequestException;
use GuzzleHttp5\Message\Request;
use GuzzleHttp5\Message\Response;
use GuzzleHttp5\Stream\Stream;
use GuzzleHttp5\Subscriber\History;
use GuzzleHttp5\Subscriber\Mock;

/**
 * @covers GuzzleHttp5\Subscriber\History
 */
class HistoryTest extends \PHPUnit_Framework_TestCase
{
    public function testAddsForErrorEvent()
    {
        $request = new Request('GET', '/');
        $response = new Response(400);
        $t = new Transaction(new Client(), $request);
        $t->response = $response;
        $e = new RequestException('foo', $request, $response);
        $ev = new ErrorEvent($t, $e);
        $h = new History(2);
        $h->onError($ev);
        // Only tracks when no response is present
        $this->assertEquals([], $h->getRequests());
    }

    public function testLogsConnectionErrors()
    {
        $request = new Request('GET', '/');
        $t = new Transaction(new Client(), $request);
        $e = new RequestException('foo', $request);
        $ev = new ErrorEvent($t, $e);
        $h = new History();
        $h->onError($ev);
        $this->assertEquals([$request], $h->getRequests());
    }

    public function testMaintainsLimitValue()
    {
        $request = new Request('GET', '/');
        $response = new Response(200);
        $t = new Transaction(new Client(), $request);
        $t->response = $response;
        $ev = new CompleteEvent($t);
        $h = new History(2);
        $h->onComplete($ev);
        $h->onComplete($ev);
        $h->onComplete($ev);
        $this->assertEquals(2, count($h));
        $this->assertSame($request, $h->getLastRequest());
        $this->assertSame($response, $h->getLastResponse());
        foreach ($h as $trans) {
            $this->assertInstanceOf('GuzzleHttp5\Message\RequestInterface', $trans['request']);
            $this->assertInstanceOf('GuzzleHttp5\Message\ResponseInterface', $trans['response']);
        }
        return $h;
    }

    /**
     * @depends testMaintainsLimitValue
     */
    public function testClearsHistory($h)
    {
        $this->assertEquals(2, count($h));
        $h->clear();
        $this->assertEquals(0, count($h));
    }

    public function testWorksWithMock()
    {
        $client = new Client(['base_url' => 'http://localhost/']);
        $h = new History();
        $client->getEmitter()->attach($h);
        $mock = new Mock([new Response(200), new Response(201), new Response(202)]);
        $client->getEmitter()->attach($mock);
        $request = $client->createRequest('GET', '/');
        $client->send($request);
        $request->setMethod('PUT');
        $client->send($request);
        $request->setMethod('POST');
        $client->send($request);
        $this->assertEquals(3, count($h));

        $result = implode("\n", array_map(function ($line) {
            return strpos($line, 'User-Agent') === 0
                ? 'User-Agent:'
                : trim($line);
        }, explode("\n", $h)));

        $this->assertEquals("> GET / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 200 OK

> PUT / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 201 Created

> POST / HTTP/1.1
Host: localhost
User-Agent:

< HTTP/1.1 202 Accepted
", $result);
    }

    public function testCanCastToString()
    {
        $client = new Client(['base_url' => 'http://localhost/']);
        $h = new History();
        $client->getEmitter()->attach($h);

        $mock = new Mock(array(
            new Response(301, array('Location' => '/redirect1', 'Content-Length' => 0)),
            new Response(307, array('Location' => '/redirect2', 'Content-Length' => 0)),
            new Response(200, array('Content-Length' => '2'), Stream::factory('HI'))
        ));

        $client->getEmitter()->attach($mock);
        $request = $client->createRequest('GET', '/');
        $client->send($request);
        $this->assertEquals(3, count($h));

        $h = str_replace("\r", '', $h);
        $this->assertContains("> GET / HTTP/1.1\nHost: localhost\nUser-Agent:", $h);
        $this->assertContains("< HTTP/1.1 301 Moved Permanently\nLocation: /redirect1", $h);
        $this->assertContains("< HTTP/1.1 307 Temporary Redirect\nLocation: /redirect2", $h);
        $this->assertContains("< HTTP/1.1 200 OK\nContent-Length: 2\n\nHI", $h);
    }
}
