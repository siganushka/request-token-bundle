<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

use Symfony\Component\Uid\Factory\NameBasedUuidFactory;
use Symfony\Component\Uid\Factory\RandomBasedUuidFactory;
use Symfony\Component\Uid\Factory\TimeBasedUuidFactory;
use Symfony\Component\Uid\Factory\UuidFactory;

class UuidTokenGenerator implements RequestTokenGeneratorInterface
{
    private UuidFactory|NameBasedUuidFactory|RandomBasedUuidFactory|TimeBasedUuidFactory $factory;

    public function __construct(UuidFactory $factory = null)
    {
        $this->factory = $factory ?? new UuidFactory();
    }

    public function generate(): string
    {
        return (string) $this->factory->create();
    }
}
