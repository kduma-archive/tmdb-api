<?php
namespace GuzzleHttp5\Tests\Event;

use GuzzleHttp5\Event\HasEmitterInterface;
use GuzzleHttp5\Event\HasEmitterTrait;
use GuzzleHttp5\Event\ListenerAttacherTrait;

class ObjectWithEvents implements HasEmitterInterface
{
    use HasEmitterTrait, ListenerAttacherTrait;

    public $listeners = [];

    public function __construct(array $args = [])
    {
        $this->listeners = $this->prepareListeners($args, ['foo', 'bar']);
        $this->attachListeners($this, $this->listeners);
    }
}

class ListenerAttacherTraitTest extends \PHPUnit_Framework_TestCase
{
    public function testRegistersEvents()
    {
        $fn = function () {};
        $o = new ObjectWithEvents([
            'foo' => $fn,
            'bar' => $fn,
        ]);

        $this->assertEquals([
            ['name' => 'foo', 'fn' => $fn, 'priority' => 0, 'once' => false],
            ['name' => 'bar', 'fn' => $fn, 'priority' => 0, 'once' => false],
        ], $o->listeners);

        $this->assertCount(1, $o->getEmitter()->listeners('foo'));
        $this->assertCount(1, $o->getEmitter()->listeners('bar'));
    }

    public function testRegistersEventsWithPriorities()
    {
        $fn = function () {};
        $o = new ObjectWithEvents([
            'foo' => ['fn' => $fn, 'priority' => 99, 'once' => true],
            'bar' => ['fn' => $fn, 'priority' => 50],
        ]);

        $this->assertEquals([
            ['name' => 'foo', 'fn' => $fn, 'priority' => 99, 'once' => true],
            ['name' => 'bar', 'fn' => $fn, 'priority' => 50, 'once' => false],
        ], $o->listeners);
    }

    public function testRegistersMultipleEvents()
    {
        $fn = function () {};
        $eventArray = [['fn' => $fn], ['fn' => $fn]];
        $o = new ObjectWithEvents([
            'foo' => $eventArray,
            'bar' => $eventArray,
        ]);

        $this->assertEquals([
            ['name' => 'foo', 'fn' => $fn, 'priority' => 0, 'once' => false],
            ['name' => 'foo', 'fn' => $fn, 'priority' => 0, 'once' => false],
            ['name' => 'bar', 'fn' => $fn, 'priority' => 0, 'once' => false],
            ['name' => 'bar', 'fn' => $fn, 'priority' => 0, 'once' => false],
        ], $o->listeners);

        $this->assertCount(2, $o->getEmitter()->listeners('foo'));
        $this->assertCount(2, $o->getEmitter()->listeners('bar'));
    }

    public function testRegistersEventsWithOnce()
    {
        $called = 0;
        $fn = function () use (&$called) { $called++; };
        $o = new ObjectWithEvents(['foo' => ['fn' => $fn, 'once' => true]]);
        $ev = $this->getMock('GuzzleHttp5\Event\EventInterface');
        $o->getEmitter()->emit('foo', $ev);
        $o->getEmitter()->emit('foo', $ev);
        $this->assertEquals(1, $called);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesEvents()
    {
        new ObjectWithEvents(['foo' => 'bar']);
    }
}
