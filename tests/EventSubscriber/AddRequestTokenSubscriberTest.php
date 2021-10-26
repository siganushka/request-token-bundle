<?php

namespace Siganushka\RequestTokenBundle\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\Configuration;
use Siganushka\RequestTokenBundle\RequestTokenGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AddRequestTokenSubscriberTest extends TestCase
{
    public function testAddRequestToken(): void
    {
        $tokenValue = '123';
        $requestTokenGenerator = $this->getMockForAbstractClass(RequestTokenGeneratorInterface::class);
        $requestTokenGenerator->expects(static::any())
            ->method('generate')
            ->willReturn($tokenValue)
        ;

        $request = new Request();
        $response = new Response();

        static::assertFalse($request->headers->has(Configuration::HEADER_NAME));
        static::assertFalse($response->headers->has(Configuration::HEADER_NAME));

        $httpKernel = $this->createMock(HttpKernelInterface::class);
        $requestEvent = new RequestEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $responseEvent = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);

        $listener = new AddRequestTokenSubscriber($requestTokenGenerator);
        $listener->onKernelRequest($requestEvent);
        $listener->onKernelResponse($responseEvent);

        static::assertTrue($request->headers->has(Configuration::HEADER_NAME));
        static::assertTrue($response->headers->has(Configuration::HEADER_NAME));
        static::assertSame($tokenValue, $request->headers->get(Configuration::HEADER_NAME));
        static::assertSame($tokenValue, $response->headers->get(Configuration::HEADER_NAME));

        // test send headers from client.
        $clientTokenValue = '000111222';
        $request->headers->set(Configuration::HEADER_NAME, $clientTokenValue);

        $requestEvent = new RequestEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST);
        $responseEvent = new ResponseEvent($httpKernel, $request, HttpKernelInterface::MAIN_REQUEST, $response);

        $listener = new AddRequestTokenSubscriber($requestTokenGenerator);
        $listener->onKernelRequest($requestEvent);
        $listener->onKernelResponse($responseEvent);

        static::assertSame($clientTokenValue, $request->headers->get(Configuration::HEADER_NAME));
        static::assertSame($clientTokenValue, $response->headers->get(Configuration::HEADER_NAME));
    }
}
