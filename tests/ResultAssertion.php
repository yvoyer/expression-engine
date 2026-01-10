<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\Value\ExpressionValue;
use PHPUnit\Framework\Assert;

final readonly class ResultAssertion
{
    public function __construct(
        private ExpressionValue $value,
    ) {
    }

    public function assertValueIsInteger(int $expected): self
    {
        Assert::assertSame($expected, $this->value->toInteger());

        return $this;
    }

    public function assertValueIsFloat(float $expected): self
    {
        Assert::assertSame($expected, $this->value->toFloat());

        return $this;
    }

    public function assertValueIsString(string $expected): self
    {
        Assert::assertSame($expected, $this->value->toString());

        return $this;
    }

    public function assertHumanReadable(string $expected): self
    {
        Assert::assertSame($expected, $this->value->toHumanReadable());

        return $this;
    }

    public function assertValueIsTrue(): self
    {
        return $this->assertValueIsBoolean(true);
    }

    public function assertValueIsFalse(): self
    {
        return $this->assertValueIsBoolean(false);
    }

    private function assertValueIsBoolean(bool $expected): self
    {
        Assert::assertSame($expected, $this->value->toBoolean());

        return $this;
    }
}
