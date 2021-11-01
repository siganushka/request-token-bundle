<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\EventListener;

use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class RequestTokenListener implements EventSubscriberInterface
{
    protected $tokenGenerator;
    protected $headerName;

    public function __construct(RequestTokenGeneratorInterface $tokenGenerator, string $headerName)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->headerName = $headerName;
    }

    public function onRequest(RequestEvent $event)
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->headers->has($this->headerName)) {
            $request->headers->set($this->headerName, $this->tokenGenerator->generate());
        }
    }

    public function onResponse(ResponseEvent $event)
    {
        if (!$this->isMainRequest($event)) {
            return;
        }

        $request = $event->getRequest();
        if ($token = $request->headers->get($this->headerName)) {
            $event->getResponse()->headers->set($this->headerName, $token);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => ['onRequest', 4096],
            ResponseEvent::class => ['onResponse', 4096],
        ];
    }

    private function isMainRequest(KernelEvent $event): bool
    {
        if (method_exists($event, 'isMainRequest')) {
            return $event->isMainRequest();
        }

        // fallback symfony 4.x
        return $event->isMasterRequest();
    }
}
