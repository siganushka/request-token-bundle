<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\RequestTokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AddRequestTokenSubscriberTest extends TestCase
{
    public function testAddRequestToken()
    {
        $requestHeader = 'bar';

        $request = new Request();
        $request->headers->set($requestHeader, '123');

        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $requestEvent = new RequestEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $tokenGenerator = $this->getMockForAbstractClass(RequestTokenGeneratorInterface::class);
        $tokenGenerator->expects(static::any())
            ->method('generate')
            ->willReturn('789')
        ;

        $listener = new AddRequestTokenSubscriber($tokenGenerator, $requestHeader);
        $listener->onKernelRequest($requestEvent);

        static::assertTrue($request->headers->has($requestHeader));
        static::assertSame('123', $request->headers->get($requestHeader));

        $request->headers->remove($requestHeader);
        $listener->onKernelRequest($requestEvent);

        static::assertTrue($request->headers->has($requestHeader));
        static::assertSame('789', $request->headers->get($requestHeader));
    }
}
