<?php

declare(strict_types=1);

namespace Siganushka\RequestTokenBundle\Tests\Generator;

use PHPUnit\Framework\TestCase;
use Siganushka\RequestTokenBundle\Generator\UniqidTokenGenerator;

class UniqidTokenGeneratorTest extends TestCase
{
    public function testGenerate(): void
    {
        $generator = new UniqidTokenGenerator();
        $token = $generator->generate();

        static::assertNotEmpty($token);
    }
}
