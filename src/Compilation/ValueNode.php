<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use Star\Component\ExpressionEngine\Value\ExpressionValue;

final readonly class ValueNode implements ExpressionNode
{
    public function __construct(
        private ExpressionValue $value,
    ) {
    }

    public function compile(array $context): string
    {
        return $this->value->toString();
    }
}
