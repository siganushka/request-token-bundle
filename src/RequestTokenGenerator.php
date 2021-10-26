<?php

namespace Siganushka\RequestTokenBundle;

class RequestTokenGenerator implements RequestTokenGeneratorInterface
{
    private $entropy;

    public function __construct(int $entropy = 64)
    {
        $this->entropy = $entropy;
    }

    public function generate(): string
    {
        return bin2hex(random_bytes($this->entropy / 8));
    }
}
