<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use function array_map;
use function gettype;
use function is_array;
use function is_bool;
use function is_int;
use function is_numeric;
use function is_string;
use function sprintf;

final readonly class ValueGuesser
{
    /**
     * @param int|float|string|bool|object|array<int|string, mixed> $value
     */
    public static function guessValue(
        int|float|string|bool|object|array $value,
    ): ExpressionValue {
        if (is_int($value)) {
            return IntegerValue::fromInteger($value);
        }

        if (is_float($value)) {
            return FloatValue::fromFloat($value);
        }

        if (is_string($value)) {
            if (is_numeric($value)) {
                if ($value == (int) $value) { // @phpstan-ignore-line
                    return IntegerValue::fromInteger((int) $value);
                } else {
                    return FloatValue::fromFloat((float) $value);
                }
            }

            return StringValue::fromString($value);
        }

        if (is_bool($value)) {
            return BooleanValue::fromBoolean($value);
        }

        if (is_array($value)) {
            return new ArrayOfValues(
                ...array_map(
                    function ($element): ExpressionValue {
                        /**
                         * @var int|float|string|bool|object|array<int|string, mixed> $element
                         */
                        return ValueGuesser::guessValue($element);
                    },
                    $value
                ),
            );
        }

        throw new NotSupportedValue(
            sprintf('Value of type "%s" is not supported.', gettype($value))
        );
    }
}
