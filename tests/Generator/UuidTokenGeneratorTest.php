<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\UuidTokenGenerator;
use Symfony\Component\Uid\Uuid;

class UuidTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new UuidTokenGenerator();
        $token = $generator->generate();

        static::assertNotEmpty($token);
        static::assertTrue(Uuid::isValid($token));
    }
}
