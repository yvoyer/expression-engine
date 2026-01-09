<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine;

use Star\Component\ExpressionEngine\Compilation\ArgumentNode;
use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\FunctionNode;
use Star\Component\ExpressionEngine\Compilation\Math\AddNode;
use Star\Component\ExpressionEngine\Compilation\Math\DivideNode;
use Star\Component\ExpressionEngine\Compilation\ValueNode;
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
                '/' => new DivideNode(
                    $this->compileNode($node->nodes['left']),
                    $this->compileNode($node->nodes['right']),
                ),

                default => throw new RuntimeException(
                    sprintf('Operator of type "%s" is not supported yet.', $node->attributes['operator'])
                ),
            };
        } elseif ($node instanceof Node\ConstantNode) {
            return new ValueNode(ValueGuesser::guessScalar($node->attributes['value']));
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
