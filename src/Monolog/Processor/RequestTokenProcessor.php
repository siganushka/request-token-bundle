<?php

namespace Siganushka\RequestTokenBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestTokenProcessor
{
    protected $requestStack;
    protected $requestHeader;

    public function __construct(RequestStack $requestStack, string $requestHeader)
    {
        $this->requestStack = $requestStack;
        $this->requestHeader = $requestHeader;
    }

    public function __invoke(array $record): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \LogicException('Request should exist so it can be processed for error.');
        }

        if (!$request->headers->has($this->requestHeader)) {
            return $record;
        }

        $record['extra'][$this->requestHeader] = $request->headers->get($this->requestHeader);

        return $record;
    }
}
