<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use function sprintf;

final readonly class BooleanValue implements ExpressionValue
{
    use ThrowExceptionWhenNotSupported;

    private function __construct(
        private bool $value,
    ) {
    }

    public function isCastableToBoolean(): bool
    {
        return true;
    }

    public function toBoolean(): bool
    {
        return $this->value;
    }

    public function toHumanReadable(): string
    {
        return sprintf('Boolean(%s)', ($this->value) ? 'true' : 'false');
    }

    public static function fromBoolean(bool $value): ExpressionValue
    {
        return new self($value);
    }
}
