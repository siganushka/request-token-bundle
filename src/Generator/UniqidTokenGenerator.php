<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

/**
 * Uniqid token generator.
 */
class UniqidTokenGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        return uniqid();
    }
}
