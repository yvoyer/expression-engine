<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Number;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use function sprintf;

final readonly class GreaterEqualNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $left,
        private ExpressionNode $right,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s >= %s',
            $this->left->compile($context),
            $this->right->compile($context),
        );
    }
}
