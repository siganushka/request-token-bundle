<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

interface RequestTokenGeneratorInterface
{
    /**
     * Generate unique request token.
     */
    public function generate(): string;
}
