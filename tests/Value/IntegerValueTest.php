<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\IntegerValue;
use PHPUnit\Framework\TestCase;

final class IntegerValueTest extends TestCase
{
    public function test_it_should_return_raw_value(): void
    {
        self::assertSame(12, IntegerValue::fromInteger(12)->toInteger());
    }

    public function test_it_should_be_castable_to_float(): void
    {
        $value = IntegerValue::fromInteger(12);
        self::assertTrue($value->isCastableToFloat());
        self::assertSame(12.0, $value->toFloat());
    }

    public function test_it_should_be_castable_to_string(): void
    {
        $value = IntegerValue::fromInteger(12);
        self::assertTrue($value->isCastableToString());
        self::assertSame('12', $value->toString());
    }

    public function test_it_should_return_readable_value(): void
    {
        self::assertSame('Integer(42)', IntegerValue::fromInteger(42)->toHumanReadable());
    }
}
