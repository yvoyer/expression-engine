<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Definition;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Definition\FunctionArguments;
use Star\Component\ExpressionEngine\Definition\FunctionContext;
use Star\Component\ExpressionEngine\Definition\FunctionDefinition;
use Star\Component\ExpressionEngine\Definition\MissingReturnValue;
use Star\Component\ExpressionEngine\Typing\IntegerType;
use function func_get_args;

final class FunctionDefinitionTest extends TestCase
{
    public function test_it_should_keep_case_name(): void
    {
        $definition = new FunctionDefinition(
            'MyFunctionNAME',
            function () {
            },
            function () {
            }
        );

        self::assertSame(
            'MyFunctionNAME',
            $definition->createFunction()->getName()
        );
    }

    public function test_it_should_wrap_context_in_custom_object(): void
    {
        $definition = new FunctionDefinition(
            'name',
            function () {
            },
            function ($context, $arguments) {
                Assert::assertCount(2, func_get_args());
                Assert::assertInstanceOf(FunctionContext::class, $context);
                Assert::assertInstanceOf(FunctionArguments::class, $arguments);
                Assert::assertSame(1, $arguments->getValueByPosition(0)->toInteger());
                Assert::assertSame(2, $arguments->getValueByPosition(1)->toInteger());
                Assert::assertSame(3, $arguments->getValueByPosition(2)->toInteger());
                return true;
            }
        )
            ->addNamedArgument(
                new ArgumentDefinition('one', 0, new IntegerType())
            )
            ->addNamedArgument(
                new ArgumentDefinition('two', 1, new IntegerType())
            )
            ->addNamedArgument(
                new ArgumentDefinition('three', 2, new IntegerType())
            )
        ;

        $callback = $definition->createFunction()->getEvaluator();
        self::assertTrue($callback(['key' => 'value'], 1, 2, 3));
    }

    public function test_it_should_throw_exception_when_function_do_not_return_value(): void
    {
        $definition = new FunctionDefinition(
            'name',
            function () {
            },
            function () {
            }
        );

        $callback = $definition->createFunction()->getEvaluator();

        $this->expectException(MissingReturnValue::class);
        $this->expectExceptionMessage('Function "name()" is missing a return value.');
        $callback([]);
    }
}
