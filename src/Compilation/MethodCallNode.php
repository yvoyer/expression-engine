<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use function array_map;
use function implode;
use function sprintf;

final readonly class MethodCallNode implements ExpressionNode
{
    /**
     * @var MethodArgument[]
     */
    private array $arguments;

    public function __construct(
        private string $variable,
        private string $method,
        MethodArgument ...$arguments
    ) {
        $this->arguments = $arguments;
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s.%s(%s)',
            $this->variable,
            $this->method,
            implode(
                ', ',
                array_map(
                    function (MethodArgument $node) use ($context): string {
                        return $node->compile($context);
                    },
                    $this->arguments,
                ),
            ),
        );
    }
}
