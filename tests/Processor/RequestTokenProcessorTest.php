<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Processor;

use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Processor\RequestTokenProcessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestTokenProcessorTest extends TestCase
{
    public function testInvokeWithoutCurrentRequest(): void
    {
        $processor = new RequestTokenProcessor(new RequestStack(), 'X-Request-Id');

        $record = $processor($this->createRecord());

        static::assertSame([], $record->extra);
    }

    public function testInvokeWithRequestToken(): void
    {
        $request = new Request();
        $request->headers->set('X-Request-Id', 'token');

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $processor = new RequestTokenProcessor($requestStack, 'X-Request-Id');

        $record = $processor($this->createRecord());

        static::assertSame(['X-Request-Id' => 'token'], $record->extra);
    }

    public function testInvokeWithoutRequestToken(): void
    {
        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        $processor = new RequestTokenProcessor($requestStack, 'X-Request-Id');

        $record = $processor($this->createRecord());

        static::assertSame([], $record->extra);
    }

    private function createRecord(): LogRecord
    {
        return new LogRecord(new \DateTimeImmutable(), 'app', Level::Info, 'message');
    }
}
