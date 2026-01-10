<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Functions\Stub;

use Closure;
use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Definition\FunctionDefinition;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;

final readonly class CallableFunction implements ExpressionFunction
{
    public function __construct(
        private FunctionDefinition $definition,
    ) {
    }

    public function createDefinition(): FunctionDefinition
    {
        return $this->definition;
    }

    public static function withArg(
        string $name,
        Closure $function,
        ArgumentDefinition $argument,
    ): self {
        return new self(
            new FunctionDefinition(
                $name,
                function () {
                },
                $function,
            )->addNamedArgument($argument)
        );
    }
}
