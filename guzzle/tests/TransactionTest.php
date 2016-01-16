<?php
namespace GuzzleHttp5\Tests;

use GuzzleHttp5\Client;
use GuzzleHttp5\Message\Request;
use GuzzleHttp5\Message\Response;
use GuzzleHttp5\Transaction;

class TransactionTest extends \PHPUnit_Framework_TestCase
{
    public function testHoldsData()
    {
        $client = new Client();
        $request = new Request('GET', 'http://www.foo.com');
        $t = new Transaction($client, $request);
        $this->assertSame($client, $t->client);
        $this->assertSame($request, $t->request);
        $response = new Response(200);
        $t->response = $response;
        $this->assertSame($response, $t->response);
    }
}
