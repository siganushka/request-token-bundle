<?php

namespace Siganushka\RequestTokenBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\DependencyInjection\SiganushkaRequestTokenExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SiganushkaRequestTokenExtensionTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token');
        $container->compile();

        static::assertTrue($container->hasDefinition('siganushka_request_token.token_generator'));
        static::assertTrue($container->getDefinition('siganushka_request_token.token_generator')->isPublic());
        static::assertFalse($container->hasDefinition('siganushka_request_token.request_token_subscriber'));
        static::assertFalse($container->hasDefinition('siganushka_request_token.response_token_subscriber'));
    }

    public function testWithConfigs(): void
    {
        $configs = [
            'token_generator' => 'foo',
            'request_header' => true,
            'response_header' => true,
        ];

        $container = $this->createContainer();
        $container->loadFromExtension('siganushka_request_token', $configs);
        $container->compile();

        static::assertTrue($container->hasDefinition('siganushka_request_token.token_generator'));
        static::assertTrue($container->getDefinition('siganushka_request_token.token_generator')->isPublic());
        static::assertTrue($container->hasDefinition('siganushka_request_token.request_token_subscriber'));
        static::assertTrue($container->hasDefinition('siganushka_request_token.response_token_subscriber'));

        $tokenGenerator = $container->getDefinition('siganushka_request_token.request_token_subscriber')->getArgument(0);
        static::assertSame($configs['token_generator'], (string) $tokenGenerator);
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
