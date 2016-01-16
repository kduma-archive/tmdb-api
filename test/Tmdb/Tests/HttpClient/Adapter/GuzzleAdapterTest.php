<?php
/**
 * This file is part of the Tmdb PHP API created by Michael Roterman.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Tmdb
 * @author Michael Roterman <michael@wtfz.net>
 * @copyright (c) 2013, Michael Roterman
 * @version 0.0.1
 */
namespace Tmdb\Tests\HttpClient\Adapter;

use GuzzleHttp5\Client;
use GuzzleHttp5\Exception\RequestException;
use GuzzleHttp5\Message\Response;
use GuzzleHttp5\Stream\Stream;
use Tmdb\Exception\NullResponseException;
use Tmdb\HttpClient\Adapter\GuzzleAdapter;
use Tmdb\HttpClient\Request;
use Tmdb\Tests\TestCase;

class GuzzleAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('get')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->get(new Request());
    }

    /**
     * @test
     */
    public function shouldPost()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('post')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->post(new Request());
    }

    /**
     * @test
     */
    public function shouldPut()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('put')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->put(new Request());
    }

    /**
     * @test
     */
    public function shouldDelete()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('delete')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->delete(new Request());
    }

    /**
     * @test
     */
    public function shouldPatch()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('patch')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->patch(new Request());
    }

    /**
     * @test
     */
    public function shouldHead()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('head')
            ->will($this->returnValue(new Response(200)))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->head(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForGet()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('get')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('get', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->get(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForPost()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('post')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('post', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->post(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForHead()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('head')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('head', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->head(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForPatch()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('patch')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('patch', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->patch(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForPut()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('put')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('put', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->put(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\TmdbApiException
     * @test
     */
    public function shouldThrowExceptionForDelete()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('delete')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('delete', '/'),
                    new \GuzzleHttp5\Message\Response(404, [], Stream::factory(json_encode([
                        'status_code' => 15,
                        'status_message' => 'Something went wrong'
                    ])))
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->delete(new Request());
    }

    /**
     * @expectedException \Tmdb\Exception\NullResponseException
     * @test
     */
    public function shouldThrowNullResponseException()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('get')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('get', '/'),
                    null
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);
        $adapter->get(new Request());
    }

    /**
     * @test
     */
    public function shouldFormatMessageForAnGuzzleHttpRequestException()
    {
        $client = $this->getMock('GuzzleHttp5\ClientInterface');

        $client->expects($this->once())
            ->method('get')
            ->will($this->throwException(
                new RequestException(
                    '404',
                    new \GuzzleHttp5\Message\Request('get', '/'),
                    null
                )
            ))
        ;

        $adapter = new GuzzleAdapter($client);

        try {
            $adapter->get(new Request());
        } catch (NullResponseException $e) {
            $this->assertEquals(true, false !== strpos($e->getMessage(), 'previous exception'));
        }
    }

    /**
     * @test
     */
    public function shouldCreateDefaultClientIfNotGiven()
    {
        $adapter = new GuzzleAdapter();

        $this->assertInstanceOf('GuzzleHttp5\ClientInterface', $adapter->getClient());
    }

    /**
     * @test
     */
    public function shouldBeAbleToOverrideClient()
    {
        $adapter = new GuzzleAdapter();
        $adapter->setClient(new BleepHttp());

        $this->assertInstanceOf('\Tmdb\Tests\HttpClient\Adapter\BleepHttp', $adapter->getClient());
    }
}

class BleepHttp extends Client {}
