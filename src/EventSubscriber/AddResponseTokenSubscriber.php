<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AddResponseTokenSubscriber implements EventSubscriberInterface
{
    protected $requestHeader;
    protected $responseHeader;

    public function __construct(string $requestHeader, string $responseHeader)
    {
        $this->requestHeader = $requestHeader;
        $this->responseHeader = $responseHeader;
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($token = $request->headers->get($this->requestHeader)) {
            $event->getResponse()->headers->set($this->responseHeader, $token);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            ResponseEvent::class => ['onKernelResponse', -128],
        ];
    }
}
