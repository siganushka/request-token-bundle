<?php

namespace Siganushka\RequestTokenBundle;

interface RequestTokenGeneratorInterface
{
    /**
     * Generate unique request token.
     */
    public function generate(): string;
}
