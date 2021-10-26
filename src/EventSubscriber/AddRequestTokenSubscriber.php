<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use Siganushka\RequestTokenBundle\DependencyInjection\Configuration;
use Siganushka\RequestTokenBundle\RequestTokenGeneratorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AddRequestTokenSubscriber
{
    protected $requestTokenGenerator;
    protected $requestHeader;
    protected $responseHeader;

    public function __construct(
        RequestTokenGeneratorInterface $requestTokenGenerator,
        string $requestHeader = Configuration::HEADER_NAME,
        string $responseHeader = Configuration::HEADER_NAME)
    {
        $this->requestTokenGenerator = $requestTokenGenerator;
        $this->requestHeader = $requestHeader;
        $this->responseHeader = $responseHeader;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        if (!$request->headers->has($this->requestHeader)) {
            $request->headers->set($this->requestHeader, $this->requestTokenGenerator->generate());
        }
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
}
