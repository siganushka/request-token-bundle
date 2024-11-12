<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SiganushkaRequestTokenBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
