<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use function array_map;
use function implode;
use function sprintf;

final readonly class FunctionNode implements ExpressionNode
{
    /**
     * @var array<int, ArgumentNode>
     */
    private array $arguments;

    public function __construct(
        private string $name,
        ArgumentNode ...$arguments,
    ) {
        $this->arguments = $arguments;
    }

    public function compile(array $context): string
    {
        return sprintf(
            '%s(%s)',
            $this->name,
            implode(
                ', ',
                array_map(
                    function (ArgumentNode $node) use ($context): string {
                        return $node->compile($context);
                    },
                    $this->arguments,
                ),
            ),
        );
    }
}
