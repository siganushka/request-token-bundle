<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;

class UuidTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new UuidTokenGenerator();
        $token = $generator->generate();

        static::assertNotEmpty($token);
    }
}
