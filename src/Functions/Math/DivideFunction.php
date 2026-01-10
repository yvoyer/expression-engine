<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Functions\Math;

use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Definition\FunctionArguments;
use Star\Component\ExpressionEngine\Definition\FunctionContext;
use Star\Component\ExpressionEngine\Definition\FunctionDefinition;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;
use Star\Component\ExpressionEngine\Typing\IntegerType;
use Star\Component\ExpressionEngine\Value\ValueGuesser;
use function sprintf;

/**
 * Divide a $dividend by the $divisor.
 *
 * Definition: divide(Integer $dividend, Integer $divisor): Float
 */
final readonly class DivideFunction implements ExpressionFunction
{
    public function createDefinition(): FunctionDefinition
    {
        return new FunctionDefinition(
            'divide',
            function (
                string $dividend,
                string $divisor,
            ): string {
                return sprintf(
                    '(%s / (%s === 0) ? 0 : %s)',
                    $dividend,
                    $divisor,
                    $divisor,
                );
            },
            function (
                FunctionContext $context,
                FunctionArguments $arguments,
            ): float {
                $arguments->assertArgumentCount(2);

                $dividend = $arguments->getValueByName('dividend');
                $divisor = $arguments->getValueByName('divisor');
                if ($divisor->toInteger() === 0) {
                    return 0.0;
                }

                return ValueGuesser::guessValue(
                    $dividend->toInteger() / $divisor->toInteger()
                )->toFloat();
            },
        )
            ->addNamedArgument(
                new ArgumentDefinition('dividend', 0, new IntegerType())
            )
            ->addNamedArgument(
                new ArgumentDefinition('divisor', 1, new IntegerType())
            )
        ;
    }
}
