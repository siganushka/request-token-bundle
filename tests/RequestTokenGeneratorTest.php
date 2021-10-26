<?php

namespace Siganushka\RequestTokenBundle;

use PHPUnit\Framework\TestCase;

class RequestTokenGeneratorTest extends TestCase
{
    public function testRequestTokenGenerate()
    {
        $generator = new RequestTokenGenerator();
        $token = $generator->generate();

        static::assertIsString($token);
        static::assertSame(16, mb_strlen($token));
    }
}
