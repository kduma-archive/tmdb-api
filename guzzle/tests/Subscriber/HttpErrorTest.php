<?php
namespace GuzzleHttp5\Tests\Message;

use GuzzleHttp5\Client;
use GuzzleHttp5\Event\CompleteEvent;
use GuzzleHttp5\Message\Request;
use GuzzleHttp5\Message\Response;
use GuzzleHttp5\Subscriber\HttpError;
use GuzzleHttp5\Transaction;
use GuzzleHttp5\Subscriber\Mock;

/**
 * @covers GuzzleHttp5\Subscriber\HttpError
 */
class HttpErrorTest extends \PHPUnit_Framework_TestCase
{
    public function testIgnoreSuccessfulRequests()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(200));
        (new HttpError())->onComplete($event);
    }

    /**
     * @expectedException \GuzzleHttp5\Exception\ClientException
     */
    public function testThrowsClientExceptionOnFailure()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(403));
        (new HttpError())->onComplete($event);
    }

    /**
     * @expectedException \GuzzleHttp5\Exception\ServerException
     */
    public function testThrowsServerExceptionOnFailure()
    {
        $event = $this->getEvent();
        $event->intercept(new Response(500));
        (new HttpError())->onComplete($event);
    }

    private function getEvent()
    {
        return new CompleteEvent(new Transaction(new Client(), new Request('PUT', '/')));
    }

    /**
     * @expectedException \GuzzleHttp5\Exception\ClientException
     */
    public function testFullTransaction()
    {
        $client = new Client();
        $client->getEmitter()->attach(new Mock([
            new Response(403)
        ]));
        $client->get('http://httpbin.org');
    }
}
