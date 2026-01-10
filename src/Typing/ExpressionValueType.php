<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Typing;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

interface ExpressionValueType
{
    /**
     * @see BooleanType
     */
    public const string TYPE_BOOL = 'Boolean';

    /**
     * @see IntegerType
     */
    public const string TYPE_INTEGER = 'Integer';

    /**
     * @see FloatType
     */
    public const string TYPE_FLOAT = 'Float';

    /**
     * @see StringType
     */
    public const string TYPE_STRING = 'String';

    public function isValidValue(ExpressionValue $value): bool;

    public function toHumanReadable(): string;
}
