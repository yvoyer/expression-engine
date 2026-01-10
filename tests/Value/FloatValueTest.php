<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\FloatValue;
use PHPUnit\Framework\TestCase;

final class FloatValueTest extends TestCase
{
    public function test_it_should_return_raw_value(): void
    {
        self::assertSame(12.34, FloatValue::fromFloat(12.34)->toFloat());
    }

    public function test_it_should_be_castable_to_int(): void
    {
        $value = FloatValue::fromFloat(12.34);
        self::assertTrue($value->isCastableToInteger());
        self::assertSame(12, $value->toInteger());
    }

    public function test_it_should_be_castable_to_string(): void
    {
        $value = FloatValue::fromFloat(12.34);
        self::assertTrue($value->isCastableToString());
        self::assertSame('12.34', $value->toString());
    }

    public function test_it_should_return_readable_value(): void
    {
        self::assertSame('Float(12.34)', FloatValue::fromFloat(12.34)->toHumanReadable());
    }
}
