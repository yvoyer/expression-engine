<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Definition;

use Closure;
use Star\Component\ExpressionEngine\Value\ValueGuesser;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use function array_key_exists;
use function array_keys;
use function array_map;
use function func_get_args;
use function sprintf;

final class FunctionDefinition
{
    /**
     * @var array<int, ArgumentDefinition>
     */
    private array $arguments = [];

    public function __construct(
        private readonly string $name,
        private readonly Closure $compiler,
        private readonly Closure $evaluator,
    ) {
    }

    public function addNamedArgument(
        ArgumentDefinition $definition,
    ): self {
        $this->arguments[$definition->getPosition()] = $definition;

        return $this;
    }

    public function createFunction(): ExpressionFunction
    {
        return new ExpressionFunction(
            $this->name,
            function (): mixed {
                $compiler = $this->compiler;
                return $compiler(...func_get_args());
            },
            function (
                array $context,
                int|float|string|bool|object|array ...$arguments,
            ): int|float|string|bool|object|array {
                /**
                 * @var array<string, string|int|float|bool|object> $context
                 */
                /**
                 * @var array<int, int|float|string|bool|object|array> $arguments
                 */
                /**
                 * @var array<int, FunctionArgument> $argumentObjects
                 */
                $argumentObjects = array_map(
                    function (
                        int|float|string|bool|object|array $value,
                        int $position,
                    ) use (
                        $arguments,
                    ): FunctionArgument {
                        /**
                         * @var int|float|string|bool|object|array<string, mixed> $value
                         */

                        if (!array_key_exists($position, $arguments)) {
                            throw new MissingArgumentDefinition(
                                sprintf(
                                    'Argument "%s" of function "%s()" is not defined. ' .
                                    'You must define it in your function declaration.',
                                    $position,
                                    $this->name,
                                )
                            );
                        }

                        return $this->arguments[$position]->createArgument(
                            ValueGuesser::guessScalar($value)
                        );
                    },
                    $arguments,
                    array_keys($arguments),
                );

                $evaluator = $this->evaluator;
                /**
                 * @var int|float|string|bool|object|array<string, mixed>|null $result
                 */
                $result = $evaluator(
                    new FunctionContext($this->name, $context),
                    FunctionArguments::fromCollection($this->name, ...$argumentObjects)
                );

                if (null === $result) {
                    throw new MissingReturnValue(
                        sprintf(
                            'Function "%s()" is missing a return value.',
                            $this->name,
                        )
                    );
                }

                return $result;
            },
        );
    }
}
