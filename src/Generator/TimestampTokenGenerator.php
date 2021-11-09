<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

/**
 * Timestamp token generator.
 */
class TimestampTokenGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        return str_pad((string) microtime(true), 15, '0');
    }
}
