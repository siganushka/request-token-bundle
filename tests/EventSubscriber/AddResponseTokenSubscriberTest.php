<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AddResponseTokenSubscriberTest extends TestCase
{
    public function testAddResponseToken(): void
    {
        $requestHeader = 'foo';
        $responseHeader = 'bar';

        $request = new Request();
        $response = new Response();

        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $responseEvent = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);

        $listener = new AddResponseTokenSubscriber($requestHeader, $responseHeader);
        $listener->onKernelResponse($responseEvent);

        static::assertFalse($request->headers->has($requestHeader));
        static::assertFalse($response->headers->has($responseHeader));

        $request->headers->set($requestHeader, '123');
        $listener->onKernelResponse($responseEvent);

        static::assertTrue($request->headers->has($requestHeader));
        static::assertTrue($response->headers->has($responseHeader));
        static::assertSame('123', $response->headers->get($responseHeader));
    }
}
