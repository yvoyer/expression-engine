<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\Compilation\IllegalArrayAccess;
use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Functions\Math\RoundFunction;

final class ExpressionRuntimeTest extends TestCase
{
    private function createTester(): ExpressionTester
    {
        return new ExpressionTester();
    }

    public function test_it_should_perform_math_operation(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction())
            ->evaluate('(2+3)-((4*3)/2)')
            ->assertValueIsFloat(-1);
    }

    public function test_it_should_evaluate_function(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction(), new RoundFunction())
            ->evaluate('round(5 / 2)')
            ->assertValueIsInteger(3);
    }

    public function test_it_should_evaluate_variable(): void
    {
        $this->createTester()
            ->evaluate('var', ['var' => 42])
            ->assertValueIsInteger(42);
    }

    public function test_it_should_evaluate_stdclass_variable_with_property(): void
    {
        $this->createTester()
            ->evaluate(
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
            ->evaluate('var.name()', ['var' => $object])
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
            ->evaluate('var.add(12)', ['var' => $object])
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
            ->evaluate('var.add(3, 5, 8)', ['var' => $object])
            ->assertValueIsInteger(26);
    }

    public function test_it_should_not_allow_access_as_array(): void
    {
        $tester = $this->createTester();

        $this->expectException(IllegalArrayAccess::class);
        $this->expectExceptionMessage('Cannot access variable using "[]".');
        $tester->evaluate('var["name"]', ['var' => ['name' => 'Joe']]);
    }

    public function test_it_should_allow_unary(): void
    {
        $tester = $this->createTester();
        $tester
            ->evaluate('!var', ['var' => true])
            ->assertValueIsBoolean(false);
        $tester
            ->evaluate('not var', ['var' => true])
            ->assertValueIsBoolean(false);
        $tester
            ->evaluate('-var', ['var' => 42])
            ->assertValueIsInteger(-42);
        $tester
            ->evaluate('+-var', ['var' => -42])
            ->assertValueIsInteger(42);
    }

    public function test_it_should_fail_on_division_by_zero(): void
    {
        $this->createTester()
            ->withFunctions(new DivideFunction())
            ->evaluate('3 / 0')
            ->assertValueIsFloat(0)
        ;
    }
}
