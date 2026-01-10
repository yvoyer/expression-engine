<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use function sprintf;

final readonly class PropertyCallNode implements ExpressionNode
{
    public function __construct(
        private string $variable,
        private string $property,
    ) {
    }

    public function compile(array $context): string
    {
        return sprintf('%s.%s', $this->variable, $this->property);
    }
}
