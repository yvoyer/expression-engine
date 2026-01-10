<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\BooleanValue;
use PHPUnit\Framework\TestCase;

final class BooleanValueTest extends TestCase
{
    public function test_it_should_return_raw_value(): void
    {
        self::assertTrue(BooleanValue::fromBoolean(true)->toBoolean());
        self::assertFalse(BooleanValue::fromBoolean(false)->toBoolean());
    }

    public function test_it_should_be_castable_to_string(): void
    {
        self::assertTrue(BooleanValue::fromBoolean(true)->isCastableToString());
        self::assertSame('true', BooleanValue::fromBoolean(true)->toString());
        self::assertSame('false', BooleanValue::fromBoolean(false)->toString());
    }

    public function test_it_should_return_readable_value(): void
    {
        self::assertSame('Boolean(true)', BooleanValue::fromBoolean(true)->toHumanReadable());
        self::assertSame('Boolean(false)', BooleanValue::fromBoolean(false)->toHumanReadable());
    }
}
