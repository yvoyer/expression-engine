<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Typing;

use Star\Component\ExpressionEngine\Typing\ExpressionValueType;
use Star\Component\ExpressionEngine\Typing\FloatType;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Value\BooleanValue;
use Star\Component\ExpressionEngine\Value\FloatValue;
use Star\Component\ExpressionEngine\Value\IntegerValue;
use Star\Component\ExpressionEngine\Value\StringValue;

final class FloatTypeTest extends TestCase
{
    public function test_value_should_be_valid(): void
    {
        $type = $this->createType();
        self::assertFalse($type->isValidValue(BooleanValue::fromBoolean(true)));
        self::assertFalse($type->isValidValue(BooleanValue::fromBoolean(false)));
        self::assertTrue($type->isValidValue(IntegerValue::fromInteger(123)));
        self::assertTrue($type->isValidValue(FloatValue::fromFloat(12.34)));
        self::assertFalse($type->isValidValue(StringValue::fromString('Value')));
    }

    public function test_it_should_return_readable_value(): void
    {
        self::assertSame('Float', $this->createType()->toHumanReadable());
    }

    protected function createType(): ExpressionValueType
    {
        return new FloatType();
    }
}
