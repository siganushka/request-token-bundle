<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Monolog\Processor;

use Symfony\Component\HttpFoundation\RequestStack;

class RequestTokenProcessor
{
    protected RequestStack $requestStack;
    protected string $headerName;

    public function __construct(RequestStack $requestStack, string $headerName)
    {
        $this->requestStack = $requestStack;
        $this->headerName = $headerName;
    }

    public function __invoke(array $record): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && $request->headers->has($this->headerName)) {
            $record['extra'][$this->headerName] = $request->headers->get($this->headerName);
        }

        return $record;
    }
}
