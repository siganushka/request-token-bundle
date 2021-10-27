<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use Siganushka\RequestTokenBundle\RequestTokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AddRequestTokenSubscriber implements EventSubscriberInterface
{
    protected $tokenGenerator;
    protected $requestHeader;

    public function __construct(RequestTokenGeneratorInterface $tokenGenerator, string $requestHeader)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->requestHeader = $requestHeader;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->headers->has($this->requestHeader)) {
            $request->headers->set($this->requestHeader, $this->tokenGenerator->generate());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => ['onKernelRequest', 128],
        ];
    }
}
