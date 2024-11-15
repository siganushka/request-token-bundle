<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\SiganushkaRequestTokenExtension;
use Siganushka\RequestTokenBundle\EventListener\RequestTokenListener;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Siganushka\RequestTokenBundle\Monolog\Processor\RequestTokenProcessor;
use Symfony\Component\DependencyInjection\Compiler\ResolveChildDefinitionsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaRequestTokenExtensionTest extends TestCase
{
    public function testLoadDefaultConfig(): void
    {
        $container = $this->createContainerWithConfig();

        static::assertFalse($container->hasAlias(RequestTokenGeneratorInterface::class));
        static::assertFalse($container->hasDefinition(RequestTokenListener::class));
        static::assertFalse($container->hasDefinition(TimestampTokenGenerator::class));
        static::assertFalse($container->hasDefinition(UniqidTokenGenerator::class));
        static::assertFalse($container->hasDefinition(UuidTokenGenerator::class));
        static::assertFalse($container->hasDefinition(RequestTokenProcessor::class));
    }

    public function testLoadCustomConfig(): void
    {
        $config = [
            'enabled' => true,
            'header_name' => 'asdf',
            'token_generator' => FooBarBazGenerator::class,
        ];

        $container = $this->createContainerWithConfig($config);

        static::assertTrue($container->hasAlias(RequestTokenGeneratorInterface::class));
        static::assertTrue($container->hasDefinition(RequestTokenListener::class));
        static::assertTrue($container->hasDefinition(TimestampTokenGenerator::class));
        static::assertTrue($container->hasDefinition(UniqidTokenGenerator::class));
        static::assertTrue($container->hasDefinition(UuidTokenGenerator::class));
        static::assertTrue($container->hasDefinition(RequestTokenProcessor::class));

        $requestTokenListenerDef = $container->getDefinition(RequestTokenListener::class);
        static::assertSame($config['header_name'], $requestTokenListenerDef->getArgument('$headerName'));

        $requestTokenProcessorDef = $container->getDefinition(RequestTokenProcessor::class);
        static::assertTrue($requestTokenProcessorDef->hasTag('monolog.processor'));
        static::assertSame($config['header_name'], $requestTokenProcessorDef->getArgument('$headerName'));
    }

    private function createContainerWithConfig(array $config = []): ContainerBuilder
    {
        $extension = new SiganushkaRequestTokenExtension();

        $container = new ContainerBuilder();
        $container->registerExtension($extension);
        $container->loadFromExtension($extension->getAlias(), $config);

        $container->getCompilerPassConfig()->setOptimizationPasses([new ResolveChildDefinitionsPass()]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setAfterRemovingPasses([]);
        $container->compile();

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
