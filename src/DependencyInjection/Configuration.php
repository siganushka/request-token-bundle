<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;
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
            // ->canBeEnabled()
            ->children()
                ->booleanNode('enabled')
                    ->defaultFalse()
                ->end()
                ->scalarNode('header_name')
                    ->cannotBeEmpty()
                    ->defaultValue('X-Request-Id')
                ->end()
                ->scalarNode('token_generator')
                    ->cannotBeEmpty()
                    ->defaultValue(UniqidTokenGenerator::class)
                    ->validate()
                        ->ifTrue(static fn (mixed $v): bool => \is_string($v) && !is_a($v, RequestTokenGeneratorInterface::class, true))
                        ->thenInvalid('The value must be instanceof '.RequestTokenGeneratorInterface::class.', %s given.')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
