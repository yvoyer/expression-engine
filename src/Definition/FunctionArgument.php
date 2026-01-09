<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Definition;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

final readonly class FunctionArgument
{
    public function __construct(
        private string $name,
        private int $position,
        private ExpressionValue $value,
    ) {
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): ExpressionValue
    {
        return $this->value;
    }
}
