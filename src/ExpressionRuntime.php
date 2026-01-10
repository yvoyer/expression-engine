<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine;

use Star\Component\ExpressionEngine\Compilation\ArgumentNode;
use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\FunctionNode;
use Star\Component\ExpressionEngine\Compilation\IllegalArrayAccess;
use Star\Component\ExpressionEngine\Compilation\Math\AddNode;
use Star\Component\ExpressionEngine\Compilation\Math\DivideNode;
use Star\Component\ExpressionEngine\Compilation\Math\MultiplyNode;
use Star\Component\ExpressionEngine\Compilation\Math\OperatorNode;
use Star\Component\ExpressionEngine\Compilation\Math\SubtractNode;
use Star\Component\ExpressionEngine\Compilation\MethodArgument;
use Star\Component\ExpressionEngine\Compilation\MethodCallNode;
use Star\Component\ExpressionEngine\Compilation\NotNode;
use Star\Component\ExpressionEngine\Compilation\PropertyCallNode;
use Star\Component\ExpressionEngine\Compilation\ValueNode;
use Star\Component\ExpressionEngine\Compilation\VariableNode;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\ValueGuesser;
use RuntimeException;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\Node;
use function array_keys;
use function get_class;
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
     * @param array<string, int|float|string|bool|object|array<string,mixed>> $context
     */
    public function evaluate(
        string $expression,
        array $context = [],
    ): ExpressionValue {
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

        return ValueGuesser::guessScalar($result);
    }

    private function compileNode(Node\Node $node): ExpressionNode
    {
        if ($node instanceof Node\BinaryNode) {
            return match ($node->attributes['operator']) {
                '+' => new AddNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '-' => new SubtractNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '*' => new MultiplyNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),
                '/' => new DivideNode(
                    $this->compileNode($node->nodes['left']), // @phpstan-ignore-line
                    $this->compileNode($node->nodes['right']), // @phpstan-ignore-line
                ),

                default => throw new RuntimeException(
                    sprintf(
                        'Operator of type "%s" is not supported yet.',
                        $node->attributes['operator'] // @phpstan-ignore-line
                    )
                ),
            };
        } elseif ($node instanceof Node\UnaryNode) {
            return match ($node->attributes['operator']) { // @phpstan-ignore-line
                '!', 'not' => new NotNode($this->compileNode($node->nodes['node'])), // @phpstan-ignore-line
                '-', '+' => new OperatorNode(
                    (string) $node->attributes['operator'], // @phpstan-ignore-line
                    $this->compileNode($node->nodes['node']), // @phpstan-ignore-line
                ),
            };
        } elseif ($node instanceof Node\ConstantNode) {
            return new ValueNode(ValueGuesser::guessScalar($node->attributes['value'])); // @phpstan-ignore-line
        } elseif ($node instanceof Node\NameNode) {
            return new VariableNode($node->attributes['name']); // @phpstan-ignore-line
        } elseif ($node instanceof Node\GetAttrNode) {
            if ($node->attributes['type'] === Node\GetAttrNode::PROPERTY_CALL) {
                return new PropertyCallNode(
                    (string) $node->nodes['node']->attributes['name'], // @phpstan-ignore-line
                    (string) $node->nodes['attribute']->attributes['value'], // @phpstan-ignore-line
                );
            }

            if ($node->attributes['type'] === Node\GetAttrNode::METHOD_CALL) {
                $arguments = [];
                $position = 0;
                foreach($node->nodes['arguments']->nodes as $key => $arg) { // @phpstan-ignore-line
                    /**
                     * @var int $key
                     */
                    if ($key % 2 === 1) {
                        $arguments[] = new MethodArgument(
                            $position,
                            $this->compileNode($arg) // @phpstan-ignore-line
                        );
                        $position ++;
                    }
                }

                return new MethodCallNode(
                    (string) $node->nodes['node']->attributes['name'], // @phpstan-ignore-line
                    (string) $node->nodes['attribute']->attributes['value'], // @phpstan-ignore-line
                    ...$arguments,
                );
            }

            throw new IllegalArrayAccess('Cannot access variable using "[]".');
        } elseif ($node instanceof Node\FunctionNode) {
            $arguments = [];
            foreach($node->nodes['arguments']->nodes as $arg) { // @phpstan-ignore-line
                $arguments[] = new ArgumentNode($this->compileNode($arg)); // @phpstan-ignore-line
            }

            return new FunctionNode(
                $node->attributes['name'], // @phpstan-ignore-line
                ...$arguments,
            );
        } else {
            throw new RuntimeException(
                sprintf('Node of type "%s" is not supported yet.', get_class($node))
            );
        }
    }

    public static function create(): self
    {
        return new self(new ExpressionLanguage());
    }
}
