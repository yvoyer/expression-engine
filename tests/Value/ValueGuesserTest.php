<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\ValueGuesser;
use PHPUnit\Framework\TestCase;

final class ValueGuesserTest extends TestCase
{
    public function test_it_should_return_integer(): void
    {
        self::assertSame(1, ValueGuesser::guessValue(1)->toInteger());
    }

    public function test_it_should_return_float(): void
    {
        self::assertSame(12.23, ValueGuesser::guessValue(12.23)->toFloat());
    }

    public function test_it_should_return_string(): void
    {
        self::assertSame('value', ValueGuesser::guessValue('value')->toString());
    }

    public function test_it_should_return_bool(): void
    {
        self::assertTrue(ValueGuesser::guessValue(true)->toBoolean());
    }

    public function test_it_should_return_empty_array(): void
    {
        self::assertSame(
            'Array()',
            ValueGuesser::guessValue([])->toHumanReadable()
        );
    }

    public function test_it_should_return_non_empty_array(): void
    {
        self::assertSame(
            'Array(Integer(12), String(string), Boolean(true))',
            ValueGuesser::guessValue([12, 'string', true])->toHumanReadable()
        );
    }

    public function test_it_should_return_string_int_as_int(): void
    {
        self::assertSame(
            'Integer(12)',
            ValueGuesser::guessValue('12')->toHumanReadable()
        );
    }

    public function test_it_should_return_string_float_as_float(): void
    {
        self::assertSame(
            'Float(12.34)',
            ValueGuesser::guessValue('12.34')->toHumanReadable()
        );
    }
}
