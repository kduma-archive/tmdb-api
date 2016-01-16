<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Event\HasEmitterInterface;
use GuzzleHttp5\Event\HasEmitterTrait;

class AbstractHasEmitter implements HasEmitterInterface
{
    use HasEmitterTrait;
}

/**
 * @covers GuzzleHttp5\Event\HasEmitterTrait
 */
class HasEmitterTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testHelperAttachesSubscribers()
    {
        $mock = $this->getMockBuilder('GuzzleHttp5\Tests\Event\AbstractHasEmitter')
            ->getMockForAbstractClass();

        $result = $mock->getEmitter();
        $this->assertInstanceOf('GuzzleHttp5\Event\EmitterInterface', $result);
        $result2 = $mock->getEmitter();
        $this->assertSame($result, $result2);
    }
}
