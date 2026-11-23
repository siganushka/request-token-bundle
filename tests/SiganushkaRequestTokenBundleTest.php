<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\SiganushkaRequestTokenBundle;

final class SiganushkaRequestTokenBundleTest extends TestCase
{
    public function testGetPath(): void
    {
        static::assertSame(\dirname(__DIR__), (new SiganushkaRequestTokenBundle())->getPath());
    }
}
