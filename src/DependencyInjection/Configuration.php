<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('siganushka_request_token');
        /** @var ArrayNodeDefinition */
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->info('request token configuration')
            ->canBeEnabled()
            ->children()
                ->scalarNode('header_name')
                    ->cannotBeEmpty()
                    ->defaultValue('X-Request-Id')
                ->end()
                ->scalarNode('token_generator')
                    ->cannotBeEmpty()
                    ->defaultValue('siganushka_request_token.generator.random_bytes')
                    ->validate()
                    ->ifTrue(function ($tokenGeneratorService) {
                        if (!class_exists($tokenGeneratorService)) {
                            return false;
                        }

                        return !(new \ReflectionClass($tokenGeneratorService))->implementsInterface(RequestTokenGeneratorInterface::class);
                    })
                    ->thenInvalid('The %s class must implement '.RequestTokenGeneratorInterface::class.' for using the "token_generator".')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
