<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\EventSubscriber\AddRequestTokenSubscriber;
use Siganushka\RequestTokenBundle\EventSubscriber\AddResponseTokenSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaRequestTokenExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $requestHeader = $config['request_header'] ?? [];
        $responseHeader = $config['response_header'] ?? [];

        if ($requestHeader['enabled']) {
            $container->register('siganushka_request_token.request_token_subscriber', AddRequestTokenSubscriber::class)
                ->setArgument(0, new Reference($config['token_generator']))
                ->setArgument(1, $requestHeader['name'])
                ->setPublic(true)
                ->addTag('kernel.event_subscriber')
            ;
        }

        if ($requestHeader['enabled'] && $responseHeader['enabled']) {
            $container->register('siganushka_request_token.response_token_subscriber', AddResponseTokenSubscriber::class)
                ->setArgument(0, $requestHeader['name'])
                ->setArgument(1, $responseHeader['name'])
                ->addTag('kernel.event_subscriber')
            ;
        }
    }
}
