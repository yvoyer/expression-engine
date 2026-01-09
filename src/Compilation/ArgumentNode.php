<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

final readonly class ArgumentNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $value,
    ) {
    }

    public function compile(array $context): string
    {
        return $this->value->compile($context);
    }
}
