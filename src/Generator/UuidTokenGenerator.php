<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

use Symfony\Component\Uid\Factory\UuidFactory;

class UuidTokenGenerator implements RequestTokenGeneratorInterface
{
    public function __construct(private readonly UuidFactory $factory = new UuidFactory())
    {
    }

    public function generate(): string
    {
        return $this->factory->create()->__toString();
    }
}
