<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\EventListener;

use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestTokenListener implements EventSubscriberInterface
{
    protected RequestTokenGeneratorInterface $tokenGenerator;
    protected string $headerName;

    public function __construct(RequestTokenGeneratorInterface $tokenGenerator, string $headerName)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->headerName = $headerName;
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->headers->has($this->headerName)) {
            $request->headers->set($this->headerName, $this->tokenGenerator->generate());
        }
    }

    public function onResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if ($token = $request->headers->get($this->headerName)) {
            $event->getResponse()->headers->set($this->headerName, $token);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onRequest', 4096],
            ResponseEvent::class => ['onResponse', 4096],
        ];
    }
}
