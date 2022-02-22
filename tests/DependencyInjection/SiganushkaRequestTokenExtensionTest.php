<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\SiganushkaRequestTokenExtension;
use Siganushka\RequestTokenBundle\Generator\RandomBytesTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaRequestTokenExtensionTest extends TestCase
{
    public function testLoadDefaultConfig(): void
    {
        $container = $this->createContainerWithConfigs([]);

        static::assertFalse($container->hasParameter('siganushka_request_token.header_name'));

        static::assertFalse($container->hasAlias('siganushka_request_token.generator'));
        static::assertFalse($container->hasAlias(RequestTokenGeneratorInterface::class));
        static::assertFalse($container->hasAlias(RandomBytesTokenGenerator::class));
        static::assertFalse($container->hasAlias(TimestampTokenGenerator::class));
        static::assertFalse($container->hasAlias(UniqidTokenGenerator::class));
        static::assertFalse($container->hasAlias(UuidTokenGenerator::class));

        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.random_bytes'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.timestamp'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.uniqid'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.uuid'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.listener.request_token'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.monolog.processor.request_token'));
    }

    public function testLoadCustomConfig(): void
    {
        $config = [
            'enabled' => true,
            'header_name' => 'asdf',
            'token_generator' => FooBarBazGenerator::class,
        ];

        $container = $this->createContainerWithConfigs([$config]);

        static::assertSame($config['header_name'], $container->getParameter('siganushka_request_token.header_name'));
        static::assertSame($config['token_generator'], (string) $container->getAlias('siganushka_request_token.generator'));
        static::assertSame($config['token_generator'], (string) $container->getAlias(RequestTokenGeneratorInterface::class));

        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.random_bytes'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.timestamp'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.uniqid'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.uuid'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.listener.request_token'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.monolog.processor.request_token'));

        $requestTokenListenerDef = $container->getDefinition('siganushka_request_token.listener.request_token');

        static::assertTrue($requestTokenListenerDef->hasTag('kernel.event_subscriber'));
        static::assertSame('siganushka_request_token.generator', (string) $requestTokenListenerDef->getArgument(0));
        static::assertSame('%siganushka_request_token.header_name%', $requestTokenListenerDef->getArgument(1));

        $requestTokenProcessorDef = $container->getDefinition('siganushka_request_token.monolog.processor.request_token');

        static::assertTrue($requestTokenProcessorDef->hasTag('monolog.processor'));
        static::assertSame('request_stack', (string) $requestTokenProcessorDef->getArgument(0));
        static::assertSame('%siganushka_request_token.header_name%', $requestTokenProcessorDef->getArgument(1));
    }

    /**
     * @param array<mixed> $configs
     */
    protected function createContainerWithConfigs(array $configs): ContainerBuilder
    {
        $container = new ContainerBuilder();

        $extension = new SiganushkaRequestTokenExtension();
        $extension->load($configs, $container);

        return $container;
    }
}

class FooBarBazGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        return 'foo_bar_baz';
    }
}
