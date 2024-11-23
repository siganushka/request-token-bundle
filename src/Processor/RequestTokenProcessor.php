<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Processor;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @see https://symfony.com/doc/current/logging/processors.html
 */
class RequestTokenProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly string $headerName)
    {
    }

    public function __invoke(LogRecord $record): LogRecord
    {
        $request = $this->requestStack->getCurrentRequest();

        if ($request && $request->headers->has($this->headerName)) {
            $record->extra[$this->headerName] = $request->headers->get($this->headerName);
        }

        return $record;
    }
}
