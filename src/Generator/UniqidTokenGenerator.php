<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

class UniqidTokenGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        return uniqid();
    }
}
