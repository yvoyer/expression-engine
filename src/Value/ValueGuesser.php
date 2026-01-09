<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use function gettype;
use function is_bool;
use function is_int;
use function is_string;
use function sprintf;

final class ValueGuesser
{
    public static function guessScalar(
        string|int|float|bool|object $value,
    ): ExpressionValue {
        if (is_int($value)) {
            return IntegerValue::fromInteger($value);
        }

        if (is_float($value)) {
            return FloatValue::fromFloat($value);
        }

        if (is_string($value)) {
            return StringValue::fromString($value);
        }

        if (is_bool($value)) {
            return BooleanValue::fromBoolean($value);
        }

        throw new NotSupportedValue(
            sprintf('Value of type "%s" is not supported.', gettype($value))
        );
    }
}
