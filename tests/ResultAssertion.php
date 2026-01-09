<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\Value\BooleanValue;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\FloatValue;
use Star\Component\ExpressionEngine\Value\IntegerValue;
use Star\Component\ExpressionEngine\Value\StringValue;
use PHPUnit\Framework\Assert;

final readonly class ResultAssertion
{
    public function __construct(
        private ExpressionValue $value,
    ) {
    }

    public function assertValueIsInteger(int $expected): self
    {
        Assert::assertInstanceOf(IntegerValue::class, $this->value);
        Assert::assertSame($expected, $this->value->toInteger());

        return $this;
    }

    public function assertValueIsFloat(float $expected): self
    {
        Assert::assertInstanceOf(FloatValue::class, $this->value);
        Assert::assertSame($expected, $this->value->toFloat());

        return $this;
    }

    public function assertValueIsString(string $expected): self
    {
        Assert::assertInstanceOf(StringValue::class, $this->value);
        Assert::assertSame($expected, $this->value->toString());

        return $this;
    }

    public function assertValueIsBoolean(bool $expected): self
    {
        Assert::assertInstanceOf(BooleanValue::class, $this->value);
        Assert::assertSame($expected, $this->value->toBoolean());

        return $this;
    }
}
