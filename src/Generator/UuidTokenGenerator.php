<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

use Symfony\Component\Uid\Uuid;

class UuidTokenGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        if (!class_exists(Uuid::class)) {
            throw new \LogicException(\sprintf('The "%s" class requires the "Uid" component. Try running "composer require symfony/uid".', self::class));
        }

        return (string) Uuid::v1();
    }
}
