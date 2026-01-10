<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

use function array_map;
use function implode;
use function sprintf;

final readonly class ArrayOfValues implements ExpressionValue
{
    use ThrowExceptionWhenNotSupported;

    /**
     * @var ExpressionValue[]
     */
    private array $elements;

    public function __construct(ExpressionValue ...$elements)
    {
        $this->elements = $elements;
    }

    public function toHumanReadable(): string
    {
        return sprintf(
            'Array(%s)',
            implode(
                ', ',
                array_map(
                    function (ExpressionValue $value): string {
                        return $value->toHumanReadable();
                    },
                    $this->elements,
                )
            )
        );
    }

    public function toCompiledString(): string
    {
        return sprintf(
            '[%s]',
            implode(
                ', ',
                array_map(
                    function (ExpressionValue $value): string {
                        return $value->toCompiledString();
                    },
                    $this->elements,
                )
            )
        );
    }
}
