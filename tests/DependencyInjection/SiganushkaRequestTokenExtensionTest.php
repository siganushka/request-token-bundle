<?php

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\SiganushkaRequestTokenExtension;
use Siganushka\RequestTokenBundle\EventSubscriber\AddRequestTokenSubscriber;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaRequestTokenExtensionTest extends TestCase
{
    public function testLoadDefaultConfig(): void
    {
        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token');
        $container->compile();

        static::assertFalse($container->hasDefinition(AddRequestTokenSubscriber::class));
    }

    public function testWithConfigs(): void
    {
        $configs = [
            'request_header' => true,
            'response_header' => true,
        ];

        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token', $configs);
        $container->compile();

        static::assertTrue($container->hasDefinition(AddRequestTokenSubscriber::class));

        $definition = $container->findDefinition(AddRequestTokenSubscriber::class);
        $listeners = $definition->getTag('kernel.event_listener');

        static::assertCount(2, $listeners);
        static::assertSame(['onKernelRequest', 'onKernelResponse'], array_column($listeners, 'method'));
    }

    protected function createContainer()
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new SiganushkaRequestTokenExtension());

        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);

        return $container;
    }
}
