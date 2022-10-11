<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\TimestampTokenGenerator;

class TimestampTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new TimestampTokenGenerator();
        $token = $generator->generate();

        static::assertNotEmpty($token);
    }
}
