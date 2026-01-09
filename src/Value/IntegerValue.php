<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use Assert\Assertion;
use function sprintf;

final readonly class IntegerValue implements ExpressionValue
{
    use ThrowExceptionWhenNotSupported;

    private function __construct(
        private int $value,
    ) {
        Assertion::between($this->value, PHP_INT_MIN, PHP_INT_MAX);
    }

    public function isCastableToInteger(): bool
    {
        return true;
    }

    public function toInteger(): int
    {
        return $this->value;
    }

    public function isCastableToFloat(): bool
    {
        return true;
    }

    public function toFloat(): float
    {
        return (float) $this->value;
    }

    public function isCastableToString(): bool
    {
        return true;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }

    public function toHumanReadable(): string
    {
        return sprintf('Integer(%s)', $this->value);
    }

    public static function fromInteger(int $value): ExpressionValue
    {
        return new self($value);
    }
}
