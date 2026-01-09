<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Definition;

use Star\Component\ExpressionEngine\Typing\ExpressionValueType;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\NotSupportedValue;
use function sprintf;

final readonly class ArgumentDefinition
{
    public function __construct(
        private string $name,
        private int $position,
        private ExpressionValueType $type,
    ) {
    }

    public function createArgument(
        ExpressionValue $value,
    ): FunctionArgument {
        if (!$this->type->isValidValue($value)) {
            throw new NotSupportedValue(
                sprintf(
                    'Value "%s" is not a supported valid value for type "%s".',
                    $value->toHumanReadable(),
                    $this->type->toHumanReadable(),
                )
            );
        }

        return new FunctionArgument(
            $this->name,
            $this->position,
            $value,
        );
    }
}
