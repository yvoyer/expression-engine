<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use function sprintf;

final readonly class TernaryNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $condition,
        private ExpressionNode $whenTrue,
        private ExpressionNode $whenFalse,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s ? %s : %s',
            $this->condition->compile($context),
            $this->whenTrue->compile($context),
            $this->whenFalse->compile($context),
        );
    }
}
