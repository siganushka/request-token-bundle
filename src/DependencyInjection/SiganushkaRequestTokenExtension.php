<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\EventSubscriber\AddRequestTokenSubscriber;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class SiganushkaRequestTokenExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        // $loader = new XmlFileLoader($container, new FileLocator(\dirname(__DIR__).'/Resources/config'));
        // $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $requestHeader = $config['request_header'] ?? [];
        $responseHeader = $config['response_header'] ?? [];

        $definition = new Definition(AddRequestTokenSubscriber::class);
        if ($requestHeader['enabled']) {
            $definition->addTag('kernel.event_listener', [
                'event' => RequestEvent::class,
                'method' => 'onKernelRequest',
                'priority' => 128,
            ]);
        }

        if ($responseHeader['enabled']) {
            $definition->addTag('kernel.event_listener', [
                'event' => ResponseEvent::class,
                'method' => 'onKernelResponse',
                'priority' => -128,
            ]);
        }

        if ($definition->getTags()) {
            $container->setDefinition(AddRequestTokenSubscriber::class, $definition);
        }
    }
}
