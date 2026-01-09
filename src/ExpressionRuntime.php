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

        return ValueGuesser::guessScalar(
            $this->env->evaluate(
                $compiled->compile($context),
                $context,
            )
        );
    }

    private function compileNode(Node\Node $node): ExpressionNode
    {
        if ($node instanceof Node\BinaryNode) {
            return match ($node->attributes['operator']) {
                '+' => new AddNode(
                    $this->compileNode($node->nodes['left']),
                    $this->compileNode($node->nodes['right']),
                ),
                '-' => new SubtractNode(
                    $this->compileNode($node->nodes['left']),
                    $this->compileNode($node->nodes['right']),
                ),
                '*' => new MultiplyNode(
                    $this->compileNode($node->nodes['left']),
                    $this->compileNode($node->nodes['right']),
                ),
                '/' => new DivideNode(
                    $this->compileNode($node->nodes['left']),
                    $this->compileNode($node->nodes['right']),
                ),

                default => throw new RuntimeException(
                    sprintf('Operator of type "%s" is not supported yet.', $node->attributes['operator'])
                ),
            };
        } elseif ($node instanceof Node\UnaryNode) {
            return match ($node->attributes['operator']) {
                '!', 'not' => new NotNode($this->compileNode($node->nodes['node'])),
                '-', '+' => new OperatorNode(
                    (string) $node->attributes['operator'],
                    $this->compileNode($node->nodes['node']),
                ),
            };
        } elseif ($node instanceof Node\ConstantNode) {
            return new ValueNode(ValueGuesser::guessScalar($node->attributes['value']));
        } elseif ($node instanceof Node\NameNode) {
            return new VariableNode($node->attributes['name']);
        } elseif ($node instanceof Node\GetAttrNode) {
            if ($node->attributes['type'] === Node\GetAttrNode::PROPERTY_CALL) {
                return new PropertyCallNode(
                    (string) $node->nodes['node']->attributes['name'],
                    (string) $node->nodes['attribute']->attributes['value'],
                );
            }

            if ($node->attributes['type'] === Node\GetAttrNode::METHOD_CALL) {
                $arguments = [];
                $position = 0;
                foreach($node->nodes['arguments']->nodes as $key => $arg) {
                    if ($key % 2 === 1) {
                        $arguments[] = new MethodArgument($position, $this->compileNode($arg));
                        $position ++;
                    }
                }

                return new MethodCallNode(
                    (string) $node->nodes['node']->attributes['name'],
                    (string) $node->nodes['attribute']->attributes['value'],
                    ...$arguments,
                );
            }

            throw new IllegalArrayAccess('Cannot access variable using "[]".');
        } elseif ($node instanceof Node\FunctionNode) {
            $arguments = [];
            foreach($node->nodes['arguments']->nodes as $arg) {
                $arguments[] = new ArgumentNode(
                    $this->compileNode($arg)
                );
            }

            return new FunctionNode(
                $node->attributes['name'],
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
