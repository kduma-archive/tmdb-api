<?php
namespace GuzzleHttp5\Subscriber\Cache;

use GuzzleHttp5\Event\BeforeEvent;
use GuzzleHttp5\Event\RequestEvents;
use GuzzleHttp5\Event\SubscriberInterface;
use GuzzleHttp5\Message\Response;

/**
 * Automatically purges a URL when a non-idempotent request is made to it.
 */
class PurgeSubscriber implements SubscriberInterface
{
    /** @var CacheStorageInterface */
    private $storage;

    /** @var array */
    private static $purgeMethods = [
        'PUT'    => true,
        'POST'   => true,
        'DELETE' => true,
        'PATCH'  => true,
        'PURGE'  => true,
    ];

    /**
     * @param CacheStorageInterface $storage Storage to modify if purging
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }

    public function getEvents()
    {
        return ['before' => ['onBefore', RequestEvents::LATE]];
    }

    public function onBefore(BeforeEvent $event)
    {
        $request = $event->getRequest();

        if (isset(self::$purgeMethods[$request->getMethod()])) {
            $this->storage->purge($request->getUrl());

            if ('PURGE' === $request->getMethod()) {
                $event->intercept(new Response(204));
            }
        }
    }
}
