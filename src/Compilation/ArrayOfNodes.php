<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Compilation;

use function array_map;
use function implode;
use function sprintf;

final readonly class ArrayOfNodes implements ExpressionNode
{
    /**
     * @var ExpressionNode[]
     */
    private array $nodes;

    public function __construct(
        ExpressionNode ...$nodes,
    ) {
        $this->nodes = $nodes;
    }

    public function compile(array $context): string
    {
        return sprintf(
            '[%s]',
            implode(
                ', ',
                array_map(
                    function (ExpressionNode $node) use (
                        $context,
                    ): string {
                        return $node->compile($context);
                    },
                    $this->nodes,
                )
            )
        );
    }
}
