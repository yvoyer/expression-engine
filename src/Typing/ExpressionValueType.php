<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Typing;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

interface ExpressionValueType
{
    /**
     * @see BooleanType
     */
    const string TYPE_BOOL = 'Boolean';

    /**
     * @see IntegerType
     */
    const string TYPE_INTEGER = 'Integer';

    /**
     * @see FloatType
     */
    const string TYPE_FLOAT = 'Float';

    /**
     * @see StringType
     */
    const string TYPE_STRING = 'String';

    public function isValidValue(ExpressionValue $value): bool;

    public function toHumanReadable(): string;
}
