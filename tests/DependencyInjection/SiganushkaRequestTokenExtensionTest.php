<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\SiganushkaRequestTokenExtension;
use Siganushka\RequestTokenBundle\Generator\RandomBytesTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaRequestTokenExtensionTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token');
        $container->compile();

        static::assertFalse($container->hasAlias(RequestTokenGeneratorInterface::class));
        static::assertFalse($container->hasAlias(RandomBytesTokenGenerator::class));
        static::assertFalse($container->hasAlias(TimestampTokenGenerator::class));
        static::assertFalse($container->hasAlias(UuidTokenGenerator::class));
        static::assertFalse($container->hasAlias(UuidTokenGenerator::class));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.random_bytes'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.timestamp'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.uniqid'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.generator.uuid'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.request_token_listener'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.request_token_processor'));
    }

    public function testWithConfigs(): void
    {
        $configs = [
            'enabled' => true,
            'header_name' => 'asdf',
            'token_generator' => FooBarBazGenerator::class,
        ];

        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token', $configs);
        $container->compile();

        static::assertTrue($container->hasAlias(RequestTokenGeneratorInterface::class));
        static::assertTrue($container->hasAlias(RandomBytesTokenGenerator::class));
        static::assertTrue($container->hasAlias(TimestampTokenGenerator::class));
        static::assertTrue($container->hasAlias(UuidTokenGenerator::class));
        static::assertTrue($container->hasAlias(UuidTokenGenerator::class));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.random_bytes'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.timestamp'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.uniqid'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.generator.uuid'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.request_token_listener'));

        $listenerDef = $container->getDefinition('siganushka_request_token.request_token_listener');
        static::assertSame($configs['token_generator'], (string) $listenerDef->getArgument(0));
        static::assertSame($configs['header_name'], (string) $listenerDef->getArgument(1));
        static::assertSame(class_exists(MonologBundle::class), $container->hasDefinition('siganushka_request_token.request_token_processor'));
    }

    protected function createContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $container->registerExtension(new SiganushkaRequestTokenExtension());

        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);

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
