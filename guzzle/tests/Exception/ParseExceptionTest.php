<?php

namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Exception\ParseException;
use GuzzleHttp5\Message\Response;

/**
 * @covers GuzzleHttp5\Exception\ParseException
 */
class ParseExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testHasResponse()
    {
        $res = new Response(200);
        $e = new ParseException('foo', $res);
        $this->assertSame($res, $e->getResponse());
        $this->assertEquals('foo', $e->getMessage());
    }
}
