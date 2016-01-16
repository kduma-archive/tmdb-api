<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Client;
use GuzzleHttp5\Event\ProgressEvent;
use GuzzleHttp5\Message\Request;
use GuzzleHttp5\Transaction;

/**
 * @covers GuzzleHttp5\Event\ProgressEvent
 */
class ProgressEventTest extends \PHPUnit_Framework_TestCase
{
    public function testContainsNumbers()
    {
        $t = new Transaction(new Client(), new Request('GET', 'http://a.com'));
        $p = new ProgressEvent($t, 2, 1, 3, 0);
        $this->assertSame($t->request, $p->getRequest());
        $this->assertSame($t->client, $p->getClient());
        $this->assertEquals(2, $p->downloadSize);
        $this->assertEquals(1, $p->downloaded);
        $this->assertEquals(3, $p->uploadSize);
        $this->assertEquals(0, $p->uploaded);
    }
}
