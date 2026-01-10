<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Functions\Math;

use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class DivideFunctionTest extends TestCase
{
    private function createTester(): ExpressionTester
    {
        return new ExpressionTester()
            ->withFunctions(new DivideFunction());
    }

    public function test_it_should_divide_by_number(): void
    {
        $this->createTester()
            ->evaluateExpression('divide(3, 4)')
            ->assertValueIsFloat(.75);
    }

    public function test_it_should_not_allow_division_by_zero(): void
    {
        $this->createTester()
            ->evaluateExpression('divide(34, 0)')
            ->assertValueIsFloat(0);
    }

    public function test_it_should_compile_function_to_valid_value(): void
    {
        $tester = $this->createTester();
        self::assertSame(
            '(34 / (3 === 0) ? 0 : 3)',
            $tester->compileExpression('divide(34, 3)')
        );
        self::assertSame(
            '(34 / (0 === 0) ? 0 : 0)',
            $tester->compileExpression('divide(34, 0)')
        );
    }
}
