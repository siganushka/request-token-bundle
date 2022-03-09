<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\Configuration;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    private ConfigurationInterface $configuration;
    private Processor $processor;

    protected function setUp(): void
    {
        $this->configuration = new Configuration();
        $this->processor = new Processor();
    }

    public function testDefaultConfig(): void
    {
        $treeBuilder = $this->configuration->getConfigTreeBuilder();
        static::assertInstanceOf(ConfigurationInterface::class, $this->configuration);
        static::assertInstanceOf(TreeBuilder::class, $treeBuilder);

        $config = $this->processor->processConfiguration($this->configuration, []);
        static::assertEquals($config, [
            'enabled' => false,
            'header_name' => 'X-Request-Id',
            'token_generator' => 'siganushka_request_token.generator.random_bytes',
        ]);
    }

    public function testEnableConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [
            [
                'enabled' => true,
            ],
        ]);

        static::assertEquals($config, [
            'enabled' => true,
            'header_name' => 'X-Request-Id',
            'token_generator' => 'siganushka_request_token.generator.random_bytes',
        ]);
    }

    public function testCustomNameConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [
            [
                'header_name' => 'foo',
                'token_generator' => FooBarGenerator::class,
            ],
        ]);

        static::assertEquals($config, [
            'enabled' => false,
            'header_name' => 'foo',
            'token_generator' => FooBarGenerator::class,
        ]);
    }

    public function testInvalidHeaderNameException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [
            [
                'header_name' => null,
            ],
        ]);
    }

    public function testInvalidTokenGeneratorException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [
            [
                'token_generator' => FooGenerator::class,
            ],
        ]);
    }
}

class FooGenerator
{
}

class FooBarGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        return 'foo_bar';
    }
}
