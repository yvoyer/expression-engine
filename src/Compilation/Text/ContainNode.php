<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Text;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use function sprintf;

final readonly class ContainNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $left,
        private ExpressionNode $right,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s contains %s',
            $this->left->compile($context),
            $this->right->compile($context),
        );
    }
}
