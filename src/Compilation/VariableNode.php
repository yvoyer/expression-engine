<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use Star\Component\ExpressionEngine\Value\ValueGuesser;
use function array_key_exists;
use function sprintf;

final readonly class VariableNode implements ExpressionNode
{
    public function __construct(
        private string $name,
    ) {
    }

    public function compile(array $context): string
    {
        if (!array_key_exists($this->name, $context)) {
            throw new UndefinedVariable(
                sprintf(
                    'Variable "%s" could not be found in context.',
                    $this->name,
                )
            );
        }

        return sprintf(
            '%s',
            ValueGuesser::guessValue($context[$this->name])->toCompiledString()
        );
    }
}
