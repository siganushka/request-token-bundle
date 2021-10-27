<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('siganushka_request_token');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('token_generator')
                    ->cannotBeEmpty()
                    ->defaultValue('siganushka_request_token.token_generator')
                ->end()
                ->arrayNode('request_header')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('name')->defaultValue('X-Request-Id')->end()
                    ->end()
                ->end()
                ->arrayNode('response_header')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('name')->defaultValue('X-Request-Id')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
