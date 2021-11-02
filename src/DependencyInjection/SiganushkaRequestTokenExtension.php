<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\DependencyInjection;

use Siganushka\RequestTokenBundle\EventListener\RequestTokenListener;
use Siganushka\RequestTokenBundle\Generator\RandomBytesTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\RequestTokenGeneratorInterface;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Siganushka\RequestTokenBundle\Monolog\Processor\RequestTokenProcessor;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SiganushkaRequestTokenExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['enabled']) {
            foreach ([
                'random_bytes' => RandomBytesTokenGenerator::class,
                'timestamp' => TimestampTokenGenerator::class,
                'uniqid' => UniqidTokenGenerator::class,
                'uuid' => UuidTokenGenerator::class,
            ] as $alias => $className) {
                $fullAlias = sprintf('siganushka_request_token.generator.%s', $alias);
                $container->register($fullAlias, $className);
                $container->setAlias($className, $fullAlias);
            }

            $container->setAlias(RequestTokenGeneratorInterface::class, $config['token_generator']);
            $container->setAlias('siganushka_request_token.generator', $config['token_generator']);

            $container->register('siganushka_request_token.listener.request_token', RequestTokenListener::class)
                ->setArgument(0, new Reference($config['token_generator']))
                ->setArgument(1, $config['header_name'])
                ->addTag('kernel.event_subscriber')
            ;

            if (class_exists(MonologBundle::class)) {
                $container->register('siganushka_request_token.monolog.processor.request_token', RequestTokenProcessor::class)
                    ->setArgument(0, new Reference('request_stack'))
                    ->setArgument(1, $config['header_name'])
                    ->addTag('monolog.processor')
                ;
            }
        }
    }
}
