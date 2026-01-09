<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use Star\Component\ExpressionEngine\Value\StringValue;
use PHPUnit\Framework\TestCase;

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
}
