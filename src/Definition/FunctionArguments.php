<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Definition;

use Star\Component\ExpressionEngine\Value\ExpressionValue;
use function array_key_exists;
use function count;
use function sprintf;
use function strtoupper;

final readonly class FunctionArguments
{
    /**
     * @var array<int, FunctionArgument>
     */
    private array $byPosition;

    /**
     * @var array<string, FunctionArgument>
     */
    private array $byName;

    private function __construct(
        private string $function,
        FunctionArgument ...$arguments
    ) {
        $byPosition = [];
        $byName = [];

        foreach ($arguments as $argument) {
            $byPosition[$argument->getPosition()] = $argument;
            $byName[$argument->getName()] = $argument;
        }

        $this->byPosition = $byPosition;
        $this->byName = $byName;
    }

    public function assertArgumentCount(int $expected): void
    {
        if (count($this->byName) !== count($this->byPosition)) {
            throw new InvalidArgumentCount(
                sprintf(
                    'Argument count is inconsistent by name (%s) and by position (%s) for function "%s()".',
                    count($this->byName),
                    count($this->byPosition),
                    strtoupper($this->function),
                )
            );
        }

        if (count($this->byName) !== $expected) {
            throw new InvalidArgumentCount(
                sprintf(
                    'Argument count for function "%s()" was expected to be exactly "%s", "%s" given.',
                    strtoupper($this->function),
                    $expected,
                    count($this->byName)
                )
            );
        }
    }

    public function getValueByName(
        string $argument
    ): ExpressionValue {
        if (!array_key_exists($argument, $this->byName)) {
            throw new ArgumentNotFound(
                sprintf(
                    'Argument "%s" of function "%s()" could not be found. Was it defined ?',
                    $argument,
                    strtoupper($this->function),
                )
            );
        }

        return $this->byName[$argument]->getValue();
    }

    public function getValueByPosition(
        int $position
    ): ExpressionValue {
        if (!array_key_exists($position, $this->byPosition)) {
            throw new ArgumentNotFound(
                sprintf(
                    'Argument of function "%s()" could not be found at position "%s". Was it defined ?',
                    strtoupper($this->function),
                    $position,
                )
            );
        }

        return $this->byPosition[$position]->getValue();
    }

    public static function fromCollection(
        string $function,
        FunctionArgument ...$arguments
    ): self {
        return new self(
            $function,
            ...$arguments
        );
    }
}
