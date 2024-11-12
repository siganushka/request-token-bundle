<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\EventListener\RequestTokenListener;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Monolog\Processor\RequestTokenProcessor;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class SiganushkaRequestTokenExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__.'/../../config'));

        $configuration = $this->getConfiguration([], $container);
        $config = $this->processConfiguration($configuration, $configs);

        if ($this->isConfigEnabled($container, $config)) {
            $loader->load('services.php');

            $container->setAlias(RequestTokenGeneratorInterface::class, $config['token_generator']);

            $requestTokenListenerDef = $container->findDefinition(RequestTokenListener::class);
            $requestTokenListenerDef->setArgument('$headerName', $config['header_name']);

            $requestTokenProcessorDef = $container->findDefinition(RequestTokenProcessor::class);
            $requestTokenProcessorDef->setArgument('$headerName', $config['header_name']);
            $requestTokenProcessorDef->addTag('monolog.processor');

            if (!class_exists(MonologBundle::class)) {
                $container->removeDefinition(RequestTokenProcessor::class);
            }
        }
    }
}
