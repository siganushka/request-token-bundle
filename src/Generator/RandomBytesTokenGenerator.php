<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Generator;

class RandomBytesTokenGenerator implements RequestTokenGeneratorInterface
{
    private $entropy;

    public function __construct(int $entropy = 128)
    {
        $this->entropy = $entropy;
    }

    public function generate(): string
    {
        return bin2hex(random_bytes($this->entropy / 8));
    }
}
