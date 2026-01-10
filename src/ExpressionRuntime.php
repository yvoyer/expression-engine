<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine;

use Star\Component\ExpressionEngine\Compilation;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\ValueGuesser;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Node;
use function array_keys;
use function get_class;
use function preg_replace;
use function sprintf;

final readonly class ExpressionRuntime
{
    public function __construct(
        private ExpressionLanguage $env,
    ) {
    }

    public function registerFunction(
        ExpressionFunction $function,
    ): void {
        $this->env->addFunction(
            $function->createDefinition()->createFunction(),
        );
    }

    /**
     * @param array<string, int|float|string|bool|object|array<int|string, mixed>> $context
     */
    public function evaluate(
        string $expression,
        array $context = [],
    ): ExpressionValue {
        $corrections = [
            '/(?<![=!<>])=(?![=<>])/' => '==', // = to ==. We do not allow assignments anyway
        ];
        foreach ($corrections as $pattern => $replacement) {
            $expression = (string) preg_replace($pattern, $replacement, $expression);
        }

        $parsedExpression = $this->env->parse($expression, array_keys($context));
        $node = $parsedExpression->getNodes();
        $compiled = $this->compileNode($node);

        /**
         * @var string|int|float|bool|object $result
         */
        $result = $this->env->evaluate(
            $compiled->compile($context),
            $context,
        );

        return ValueGuesser::guessValue($result);
    }

    /**
     * @param string $expression
     * @param array<int, string> $context
     * @return string
     */
    public function compile(
        string $expression,
        array $context = [],
    ): string {
        return $this->env->compile($expression, $context);
    }

    private function compileNode(Node\Node $node): Compilation\ExpressionNode
    {
        if ($node instanceof Node\BinaryNode) {
            return match ($node->attributes['operator']) {
                '+' => new Compilation\Number\AddNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '-' => new Compilation\Number\SubtractNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '*' => new Compilation\Number\MultiplyNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '/' => new Compilation\Number\DivideNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '**' => new Compilation\Number\PowerNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '..' => new Compilation\Number\RangeNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '==' => new Compilation\Comparator\EqualNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '!=' => new Compilation\Comparator\NotEqualNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '>' => new Compilation\Number\GreaterNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '>=' => new Compilation\Number\GreaterEqualNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '<' => new Compilation\Number\LessNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '<=' => new Compilation\Number\LessEqualNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                'in' => new Compilation\Comparator\InNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                'not in' => new Compilation\Comparator\NotInNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '||', 'or' => new Compilation\Comparator\OrNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '^' => new Compilation\Bitwise\XOrNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '&&', 'and' => new Compilation\Comparator\AndNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                'contains' => new Compilation\Text\ContainNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                'starts with' => new Compilation\Text\StartsWithNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                'ends with' => new Compilation\Text\EndsWithNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '>>' => new Compilation\Bitwise\ShiftRightNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '<<' => new Compilation\Bitwise\ShiftLeftNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '|' => new Compilation\Bitwise\OrBitNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '&' => new Compilation\Bitwise\AndBitNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '%' => new Compilation\Number\ModuloNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '~' => new Compilation\Text\ConcatNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                default => throw new Compilation\InvalidSyntax(
                    sprintf(
                        'Operator of type "%s" is not supported.',
                        $node->attributes['operator'] // @phpstan-ignore-line
                    )
                ),
            };
        } elseif ($node instanceof Node\UnaryNode) {
            return match ($node->attributes['operator']) {
                '!', 'not' => new Compilation\Comparator\NotNode(
                    $this->compileNode($node->nodes['node']), // @phpstan-ignore-line
                ),
                '-', '+' => new Compilation\Number\OperatorNode(
                    (string) $node->attributes['operator'], // @phpstan-ignore-line
                    $this->compileNode($node->nodes['node']), // @phpstan-ignore-line
                ),
                '~' => new Compilation\Bitwise\NotBitNode(
                    $this->compileNode($node->nodes['node']), // @phpstan-ignore-line
                ),
                default => throw new Compilation\InvalidSyntax(
                    sprintf(
                        'Operator of type "%s" is not supported.',
                        $node->attributes['operator'] // @phpstan-ignore-line
                    )
                ),
            };
        } elseif ($node instanceof Node\ArrayNode) {
            $elements = [];
            foreach ($node->nodes as $key => $arg) {
                /**
                 * @var int $key
                 */
                if ($key % 2 === 1) {
                    $elements[] = $this->compileNode($arg); // @phpstan-ignore-line
                }
            }

            return new Compilation\ArrayOfNodes(...$elements);
        } elseif ($node instanceof Node\ConstantNode) {
            return new Compilation\ValueNode(
                ValueGuesser::guessValue($node->attributes['value']) // @phpstan-ignore-line
            );
        } elseif ($node instanceof Node\NameNode) {
            return new Compilation\VariableNode($node->attributes['name']); // @phpstan-ignore-line
        } elseif ($node instanceof Node\GetAttrNode) {
            if ($node->attributes['type'] === Node\GetAttrNode::PROPERTY_CALL) {
                return new Compilation\PropertyCallNode(
                    (string) $node->nodes['node']->attributes['name'], // @phpstan-ignore-line
                    (string) $node->nodes['attribute']->attributes['value'], // @phpstan-ignore-line
                );
            }

            if ($node->attributes['type'] === Node\GetAttrNode::ARRAY_CALL) {
                return new Compilation\ArrayAccessNode(
                    (string) $node->nodes['node']->attributes['name'], // @phpstan-ignore-line
                    (string) $node->nodes['attribute']->attributes['value'], // @phpstan-ignore-line
                );
            }

            if ($node->attributes['type'] === Node\GetAttrNode::METHOD_CALL) {
                $arguments = [];
                $position = 0;
                foreach ($node->nodes['arguments']->nodes as $key => $arg) { // @phpstan-ignore-line
                    /**
                     * @var int $key
                     */
                    if ($key % 2 === 1) {
                        $arguments[] = new Compilation\MethodArgument(
                            $position,
                            $this->compileNode($arg) // @phpstan-ignore-line
                        );
                        $position++;
                    }
                }

                return new Compilation\MethodCallNode(
                    (string) $node->nodes['node']->attributes['name'], // @phpstan-ignore-line
                    (string) $node->nodes['attribute']->attributes['value'], // @phpstan-ignore-line
                    ...$arguments,
                );
            }

            throw new Compilation\IllegalArrayAccess('Cannot access variable using "[]".');
        } elseif ($node instanceof Node\FunctionNode) {
            $arguments = [];
            foreach ($node->nodes['arguments']->nodes as $arg) { // @phpstan-ignore-line
                $arguments[] = new Compilation\ArgumentNode($this->compileNode($arg)); // @phpstan-ignore-line
            }

            return new Compilation\FunctionNode(
                $node->attributes['name'], // @phpstan-ignore-line
                ...$arguments,
            );
        } elseif ($node instanceof Node\ConditionalNode) {
            return new Compilation\TernaryNode(
                $this->compileNode($node->nodes['expr1']), // @phpstan-ignore-line
                $this->compileNode($node->nodes['expr2']), // @phpstan-ignore-line
                $this->compileNode($node->nodes['expr3']), // @phpstan-ignore-line
            );
        } else {
            throw new Compilation\NotSupportedNode(
                sprintf('Node of type "%s" is not supported.', get_class($node))
            );
        }
    }

    public static function create(): self
    {
        return new self(new ExpressionLanguage());
    }
}
