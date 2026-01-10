<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Number;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;

final readonly class ModuloNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $left,
        private ExpressionNode $right,
    ) {
    }

    public function compile(array $context): string
    {
        return $this->left->compile($context)
            . ' % ' .
            $this->right->compile($context);
    }
}
