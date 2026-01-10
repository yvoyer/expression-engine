<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\ExpressionRuntime;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;

final readonly class ExpressionTester
{
    private ExpressionRuntime $runtime;

    public function __construct()
    {
        $this->runtime = ExpressionRuntime::create();
    }

    public function withFunctions(
        ExpressionFunction $function,
        ExpressionFunction ...$others,
    ): self {
        $this->runtime->registerFunction($function);
        foreach ($others as $other) {
            $this->runtime->registerFunction($other);
        }

        return $this;
    }

    /**
     * @param array<string, int|float|string|bool|object|array<int|string,mixed>> $context
     */
    public function evaluateExpression(
        string $expression,
        array $context = [],
    ): ResultAssertion {
        return new ResultAssertion(
            $this->runtime->evaluate($expression, $context),
        );
    }

    /**
     * @param string $expression
     * @param array<int, string> $context
     * @return string
     */
    public function compileExpression(
        string $expression,
        array $context = [],
    ): string {
        return $this->runtime->compile($expression, $context);
    }

    public static function create(): ExpressionTester
    {
        return new self();
    }
}
