<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\EventListener;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AddRequestTokenListenerTest extends TestCase
{
    public function testAddRequestToken()
    {
        $headerName = 'bar';

        $request = new Request();
        $request->headers->set($headerName, '123');

        $response = new Response();

        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $requestEvent = new RequestEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $responseEvent = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);

        $tokenGenerator = $this->getMockForAbstractClass(RequestTokenGeneratorInterface::class);
        $tokenGenerator->expects(static::any())
            ->method('generate')
            ->willReturn('789')
        ;

        $listener = new AddRequestTokenListener($tokenGenerator, $headerName);
        $listener->onKernelRequest($requestEvent);
        $listener->onKernelResponse($responseEvent);

        static::assertTrue($request->headers->has($headerName));
        static::assertSame('123', $request->headers->get($headerName));
        static::assertTrue($response->headers->has($headerName));
        static::assertSame('123', $response->headers->get($headerName));

        $request->headers->remove($headerName);
        $listener->onKernelRequest($requestEvent);
        $listener->onKernelResponse($responseEvent);

        static::assertTrue($request->headers->has($headerName));
        static::assertSame('789', $request->headers->get($headerName));
        static::assertTrue($response->headers->has($headerName));
        static::assertSame('789', $response->headers->get($headerName));
    }
}
