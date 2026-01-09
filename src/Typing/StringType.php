<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Typing;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

final class StringType implements ExpressionValueType
{
    public function isValidValue(ExpressionValue $value): bool
    {
        return $value->isCastableToString();
    }

    public function toHumanReadable(): string
    {
        return self::TYPE_STRING;
    }
}
