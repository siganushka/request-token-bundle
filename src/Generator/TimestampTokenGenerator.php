<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

class TimestampTokenGenerator implements RequestTokenGeneratorInterface
{
    public function generate(): string
    {
        $microtime = microtime();

        return \sprintf('%10s%06s', substr($microtime, -10), substr($microtime, 2, 6));
    }
}
