<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

interface ExpressionNode
{
    /**
     * Compile the node to a string executable by the engine.
     *
     * @param array<string, int|float|string|bool|object> $context
     * @return string
     */
    public function compile(array $context): string;
}
