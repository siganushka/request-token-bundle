<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const HEADER_NAME = 'X-Request-Id';

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('siganushka_request_token');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->arrayNode('request_header')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('name')->defaultValue(self::HEADER_NAME)->end()
                    ->end()
                ->end()
                ->arrayNode('response_header')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->scalarNode('name')->defaultValue(self::HEADER_NAME)->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
