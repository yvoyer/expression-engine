<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Bitwise;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use function sprintf;

final readonly class XOrNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $left,
        private ExpressionNode $right,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s ^ %s',
            $this->left->compile($context),
            $this->right->compile($context),
        );
    }
}
