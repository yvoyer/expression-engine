<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\ValueGuesser;
use PHPUnit\Framework\TestCase;

final class ValueGuesserTest extends TestCase
{
    public function test_it_should_return_integer(): void
    {
        self::assertSame(1, ValueGuesser::guessScalar(1)->toInteger());
    }

    public function test_it_should_return_float(): void
    {
        self::assertSame(12.23, ValueGuesser::guessScalar(12.23)->toFloat());
    }

    public function test_it_should_return_string(): void
    {
        self::assertSame('value', ValueGuesser::guessScalar('value')->toString());
    }

    public function test_it_should_return_bool(): void
    {
        self::assertTrue(ValueGuesser::guessScalar(true)->toBoolean());
    }
}
