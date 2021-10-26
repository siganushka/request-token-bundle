<?php

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\Processor;

/**
 * @internal
 * @coversNothing
 */
final class ConfigurationTest extends TestCase
{
    private $configuration;
    private $processor;

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
            'request_header' => [
                'enabled' => false,
                'name' => Configuration::HEADER_NAME,
            ],
            'response_header' => [
                'enabled' => false,
                'name' => Configuration::HEADER_NAME,
            ],
        ]);
    }

    public function testEnableConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [
            [
                'request_header' => true,
                'response_header' => true,
            ],
        ]);

        static::assertEquals($config, [
            'request_header' => [
                'enabled' => true,
                'name' => Configuration::HEADER_NAME,
            ],
            'response_header' => [
                'enabled' => true,
                'name' => Configuration::HEADER_NAME,
            ],
        ]);
    }

    public function testCustomNameConfig(): void
    {
        $config = $this->processor->processConfiguration($this->configuration, [
            [
                'request_header' => [
                    'name' => 'foo',
                ],
                'response_header' => [
                    'name' => 'bar',
                ],
            ],
        ]);

        static::assertEquals($config, [
            'request_header' => [
                'enabled' => true,
                'name' => 'foo',
            ],
            'response_header' => [
                'enabled' => true,
                'name' => 'bar',
            ],
        ]);
    }

    public function testInvalidEnabledException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [
            [
                'request_header' => 1,
                'response_header' => 1,
            ],
        ]);
    }

    public function testInvalidNameException(): void
    {
        $this->expectException(InvalidConfigurationException::class);

        $this->processor->processConfiguration($this->configuration, [
            [
                'request_header' => [
                    'name' => [],
                ],
                'response_header' => [
                    'name' => [],
                ],
            ],
        ]);
    }
}
