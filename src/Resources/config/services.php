<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\RequestTokenBundle\EventListener\RequestTokenListener;
use Siganushka\RequestTokenBundle\Generator\RandomBytesTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Siganushka\RequestTokenBundle\Monolog\Processor\RequestTokenProcessor;
use Symfony\Bundle\MonologBundle\MonologBundle;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('siganushka_request_token.generator.random_bytes', RandomBytesTokenGenerator::class)
        ->set('siganushka_request_token.generator.timestamp', TimestampTokenGenerator::class)
        ->set('siganushka_request_token.generator.uniqid', UniqidTokenGenerator::class)
        ->set('siganushka_request_token.generator.uuid', UuidTokenGenerator::class)

        ->set('siganushka_request_token.listener.request_token', RequestTokenListener::class)
            ->arg(0, service('siganushka_request_token.generator'))
            ->arg(1, param('siganushka_request_token.header_name'))
            ->tag('kernel.event_subscriber')
    ;

    if (class_exists(MonologBundle::class)) {
        $container->services()
            ->set('siganushka_request_token.monolog.processor.request_token', RequestTokenProcessor::class)
                ->arg(0, service('request_stack'))
                ->arg(1, param('siganushka_request_token.header_name'))
                ->tag('monolog.processor')
        ;
    }
};
