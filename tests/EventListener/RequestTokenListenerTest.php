<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
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

        /** @var MockObject&RequestTokenGeneratorInterface */
        $tokenGenerator = $this->getMockForAbstractClass(RequestTokenGeneratorInterface::class);
        $tokenGenerator->expects(static::any())
            ->method('generate')
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
}
