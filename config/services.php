<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Siganushka\RequestTokenBundle\SiganushkaRequestTokenBundle;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()
    ;

    $ref = new \ReflectionClass(SiganushkaRequestTokenBundle::class);
    $services->load($ref->getNamespaceName().'\\', '../src/')
        ->exclude([
            '../src/DependencyInjection/',
            '../src/SiganushkaRequestTokenBundle.php',
        ]);
};
