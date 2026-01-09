<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use function sprintf;

final readonly class StringValue implements ExpressionValue
{
    use ThrowExceptionWhenNotSupported;

    private function __construct(
        private string $value,
    ) {
    }

    public function isCastableToString(): bool
    {
        return true;
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function toHumanReadable(): string
    {
        return sprintf('String(%s)', $this->value);
    }

    public static function fromString(string $value): ExpressionValue
    {
        return new self($value);
    }
}
