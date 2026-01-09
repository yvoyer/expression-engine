<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

final readonly class MethodArgument implements ExpressionNode
{
    public function __construct(
        private int $position,
        private ExpressionNode $value,
    ) {
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function compile(array $context): string
    {
        return $this->value->compile($context);
    }
}
