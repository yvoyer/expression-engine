<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Functions\Math;

use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Definition\FunctionArguments;
use Star\Component\ExpressionEngine\Definition\FunctionDefinition;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;
use Star\Component\ExpressionEngine\Typing\IntegerType;

final class RoundFunction implements ExpressionFunction
{
    public function createDefinition(): FunctionDefinition
    {
        return new FunctionDefinition(
            'round',
            function (): string {
            },
            function (
                array $context,
                FunctionArguments $arguments,
            ): int {
                $arguments->assertArgumentCount(1);

                return (int) round($arguments->getValueByName('value')->toFloat());
            },
        )
            ->addNamedArgument(
                new ArgumentDefinition('value', 0, new IntegerType())
            );
    }
}
