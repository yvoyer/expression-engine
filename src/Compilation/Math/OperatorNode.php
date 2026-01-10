<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Math;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use function sprintf;

final readonly class OperatorNode implements ExpressionNode
{
    public function __construct(
        private string $operator,
        private ExpressionNode $node,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf('%s %s', $this->operator, $this->node->compile($context));
    }
}
