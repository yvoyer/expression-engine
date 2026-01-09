<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use RuntimeException;
use Star\Component\ExpressionEngine\Typing\ExpressionValueType;
use function sprintf;

/**
 * @see ExpressionValue
 */
trait ThrowExceptionWhenNotSupported
{
    public function isCastableToInteger(): bool
    {
        return false;
    }

    public function toInteger(): int
    {
        throw new ValueNotCastable(
            sprintf(
                'Value of type "%s" is not castable to "%s".',
                $this->toHumanReadable(),
                ExpressionValueType::TYPE_INTEGER,
            )
        );
    }

    public function isCastableToFloat(): bool
    {
        return false;
    }

    public function toFloat(): float
    {
        throw new ValueNotCastable(
            sprintf(
                'Value of type "%s" is not castable to "%s".',
                $this->toHumanReadable(),
                ExpressionValueType::TYPE_FLOAT,
            )
        );
    }

    public function isCastableToBoolean(): bool
    {
        return false;
    }

    public function toBoolean(): bool
    {
        throw new ValueNotCastable(
            sprintf(
                'Value of type "%s" is not castable to "%s".',
                $this->toHumanReadable(),
                ExpressionValueType::TYPE_BOOL,
            )
        );
    }

    public function isCastableToString(): bool
    {
        return false;
    }

    public function toString(): string
    {
        throw new ValueNotCastable(
            sprintf(
                'Value of type "%s" is not castable to "%s".',
                $this->toHumanReadable(),
                ExpressionValueType::TYPE_STRING,
            )
        );
    }

    public function toHumanReadable(): string
    {
        throw new RuntimeException(__METHOD__ . ' is not implemented yet.');
    }
}
