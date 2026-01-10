<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Typing;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

final readonly class IntegerType implements ExpressionValueType
{
    public function isValidValue(ExpressionValue $value): bool
    {
        return $value->isCastableToInteger();
    }

    public function toHumanReadable(): string
    {
        return self::TYPE_INTEGER;
    }
}
