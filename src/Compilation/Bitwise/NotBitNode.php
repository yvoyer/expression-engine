<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation\Bitwise;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use function sprintf;

final readonly class NotBitNode implements ExpressionNode
{
    public function __construct(
        private ExpressionNode $node,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf(
            '~ %s',
            $this->node->compile($context),
        );
    }
}
