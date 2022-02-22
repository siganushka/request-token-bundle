<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\RandomBytesTokenGenerator;

class RandomBytesTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new RandomBytesTokenGenerator();
        $token = $generator->generate();

        static::assertIsString($token);
        static::assertNotEmpty($token);
    }
}
