<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\EventListener\RequestTokenListener;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RequestTokenListenerTest extends TestCase
{
    public function testRequestToken(): void
    {
        $headerName = 'bar';

        $request = new Request();
        $request->headers->set($headerName, '123');

        $response = new Response();

        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $requestEvent = new RequestEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $responseEvent = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);

        $tokenGenerator = $this->createMock(RequestTokenGeneratorInterface::class);
        $tokenGenerator->method('generate')
            ->willReturn('789')
        ;

        $listener = new RequestTokenListener($tokenGenerator, $headerName);
        $listener->onRequest($requestEvent);
        $listener->onResponse($responseEvent);

        static::assertTrue($request->headers->has($headerName));
        static::assertSame('123', $request->headers->get($headerName));
        static::assertTrue($response->headers->has($headerName));
        static::assertSame('123', $response->headers->get($headerName));

        $request->headers->remove($headerName);
        $listener->onRequest($requestEvent);
        $listener->onResponse($responseEvent);

        static::assertTrue($request->headers->has($headerName));
        static::assertSame('789', $request->headers->get($headerName));
        static::assertTrue($response->headers->has($headerName));
        static::assertSame('789', $response->headers->get($headerName));
    }

    public function testSubRequestIsIgnored(): void
    {
        $headerName = 'X-Request-Id';

        $request = new Request();
        $response = new Response();
        $httpKernel = $this->createMock(HttpKernelInterface::class);

        $tokenGenerator = $this->createMock(RequestTokenGeneratorInterface::class);
        $tokenGenerator->expects(static::never())->method('generate');

        $listener = new RequestTokenListener($tokenGenerator, $headerName);
        $listener->onRequest(new RequestEvent($httpKernel, $request, HttpKernelInterface::SUB_REQUEST));
        $listener->onResponse(new ResponseEvent($httpKernel, $request, HttpKernelInterface::SUB_REQUEST, $response));

        static::assertFalse($request->headers->has($headerName));
        static::assertFalse($response->headers->has($headerName));
    }

    public function testResponseWithoutRequestToken(): void
    {
        $headerName = 'X-Request-Id';

        $request = new Request();
        $response = new Response();
        $httpKernel = $this->createMock(HttpKernelInterface::class);

        $listener = new RequestTokenListener($this->createMock(RequestTokenGeneratorInterface::class), $headerName);
        $listener->onResponse(new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response));

        static::assertFalse($response->headers->has($headerName));
    }

    public function testGetSubscribedEvents(): void
    {
        static::assertSame([
            RequestEvent::class => ['onRequest', 128],
            ResponseEvent::class => ['onResponse', 128],
        ], RequestTokenListener::getSubscribedEvents());
    }
}
