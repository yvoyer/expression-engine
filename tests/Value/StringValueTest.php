<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\StringValue;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Value\ValueNotCastable;

final class StringValueTest extends TestCase
{
    public function test_it_should_return_raw_value(): void
    {
        self::assertSame('value', StringValue::fromString('value')->toString());
    }

    public function test_it_should_return_readable_value(): void
    {
        self::assertSame('String(value)', StringValue::fromString('value')->toHumanReadable());
    }

    public function test_it_should_not_be_castable_to_int_when_integer(): void
    {
        self::assertFalse(
            StringValue::fromString('123')->isCastableToInteger()
        );
        $this->expectException(ValueNotCastable::class);
        $this->expectExceptionMessage('Value of type "String(123)" is not castable to "Integer".');
        StringValue::fromString('123')->toInteger();
    }
}
