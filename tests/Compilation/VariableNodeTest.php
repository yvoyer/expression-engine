<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\UndefinedVariable;
use Star\Component\ExpressionEngine\Compilation\VariableNode;
use PHPUnit\Framework\TestCase;

final class VariableNodeTest extends TestCase
{
    public function test_it_should_compile_variable_from_context(): void
    {
        self::assertSame('43', new VariableNode('var')->compile(['var' => 43]));
    }

    public function test_it_should_throw_exception_when_context_do_not_have_variable(): void
    {
        $variable = new VariableNode('name');

        $this->expectException(UndefinedVariable::class);
        $this->expectExceptionMessage('Variable "name" could not be found in context.');
        $variable->compile([]);
    }
}
