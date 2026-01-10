<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\Compilation\IllegalArrayAccess;
use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Definition\FunctionArguments;
use Star\Component\ExpressionEngine\Definition\FunctionContext;
use Star\Component\ExpressionEngine\Definition\FunctionDefinition;
use Star\Component\ExpressionEngine\ExpressionRuntime;
use Star\Component\ExpressionEngine\Functions\ExpressionFunction;
use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Functions\Math\RoundFunction;
use Star\Component\ExpressionEngine\Typing\IntegerType;
use function sprintf;

final class ExpressionRuntimeTest extends TestCase
{
    private function createTester(): ExpressionTester
    {
        return new ExpressionTester();
    }

    public function test_readme_example(): void
    {
        $engine = ExpressionRuntime::create();
        $engine->registerFunction(
            new class implements ExpressionFunction
            {
                public function createDefinition(): FunctionDefinition
                {
                    return new FunctionDefinition(
                        'ageMultiplier',
                        function (string $multiplier): string {
                            // The compiled string evaluatable for caching
                            return sprintf('$age * %s', $multiplier);
                        },
                        function (
                            FunctionContext $context,
                            FunctionArguments $arguments,
                        ): int {
                            $arguments->assertArgumentCount(1);
                            $multiplier = $arguments->getValueByName('multiplier')->toInteger();

                            return $context->getIntegerValue('age') * $multiplier;
                        },
                    )->addNamedArgument(
                        new ArgumentDefinition('multiplier', 0, new IntegerType())
                    );
                }
            }
        );
        self::assertSame(
            92,
            $engine->evaluate(
                'ageMultiplier(4)',
                [
                    'age' => 23,
                ]
            )->toInteger()
        );
        self::assertSame(
            '$age * 4',
            $engine->compile('ageMultiplier(4)', ['age'])
        );
    }

    public function test_it_should_perform_math_operation(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction())
            ->evaluateExpression('(2+3)-((4*3)/2)')
            ->assertValueIsFloat(-1);
    }

    public function test_it_should_evaluate_function(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction(), new RoundFunction())
            ->evaluateExpression('round(5 / 2)')
            ->assertValueIsInteger(3);
    }

    public function test_it_should_evaluate_variable(): void
    {
        $this->createTester()
            ->evaluateExpression('var', ['var' => 42])
            ->assertValueIsInteger(42);
    }

    public function test_it_should_evaluate_stdclass_variable_with_property(): void
    {
        $this->createTester()
            ->evaluateExpression(
                'var.name',
                [
                    'var' => (object) ['name' => 'Joe'],
                ]
            )
            ->assertValueIsString('Joe');
    }

    public function test_it_should_evaluate_variable_with_method_no_args(): void
    {
        $object = new class {
            public function name(): string
            {
                return 'Joe';
            }
        };

        $this->createTester()
            ->evaluateExpression('var.name()', ['var' => $object])
            ->assertValueIsString('Joe');
    }

    public function test_it_should_evaluate_variable_with_method_one_arg(): void
    {
        $object = new class {
            public function add(int $value): int
            {
                return 10 + $value;
            }
        };

        $this->createTester()
            ->evaluateExpression('var.add(12)', ['var' => $object])
            ->assertValueIsInteger(22);
    }

    public function test_it_should_evaluate_variable_with_method_many_args(): void
    {
        $object = new class {
            public function add(
                int $one,
                int $two,
                int $three,
            ): int {
                return 10 + $one + $two + $three;
            }
        };

        $this->createTester()
            ->evaluateExpression('var.add(3, 5, 8)', ['var' => $object])
            ->assertValueIsInteger(26);
    }

    public function test_it_should_allow_unary(): void
    {
        $tester = $this->createTester();
        $tester
            ->evaluateExpression('!var', ['var' => true])
            ->assertValueIsFalse();
        $tester
            ->evaluateExpression('not var', ['var' => true])
            ->assertValueIsFalse();
        $tester
            ->evaluateExpression('-var', ['var' => 42])
            ->assertValueIsInteger(-42);
        $tester
            ->evaluateExpression('+-var', ['var' => -42])
            ->assertValueIsInteger(42);
    }

    public function test_it_should_fail_on_division_by_zero(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction())
            ->evaluateExpression('3 / 0')
            ->assertValueIsFloat(0)
        ;
    }

    public function test_it_should_support_boolean_operators(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction())
            ->evaluateExpression('(12 > 4 || 10 < 20) && (4 != 5)')
            ->assertValueIsTrue()
        ;
    }

    public function test_it_should_support_ternary_operations(): void
    {
        $this->createTester()
            ->evaluateExpression('(12 > 4) ? "yes": "no"')
            ->assertValueIsString('yes')
        ;
    }

    public function test_it_should_support_in_array(): void
    {
        $this->createTester()
            ->evaluateExpression('var in [12, 34]', ['var' => 12])
            ->assertValueIsTrue()
        ;
    }
}
