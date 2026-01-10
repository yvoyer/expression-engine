<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ValueNodeTest extends TestCase
{
    public function test_it_should_compile_string(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('"Joe"')
            ->assertValueIsString('Joe');
    }

    public function test_it_should_compile_int(): void
    {
        $tester = ExpressionTester::create();
        $tester
            ->evaluateExpression('12')
            ->assertValueIsInteger(12);
        $tester
            ->evaluateExpression('"12"')
            ->assertValueIsString("12");
    }

    public function test_it_should_compile_float(): void
    {
        $tester = ExpressionTester::create();
        $tester
            ->evaluateExpression('12.34')
            ->assertValueIsFloat(12.34);
        $tester
            ->evaluateExpression('"12.34"')
            ->assertValueIsString("12.34");
    }

    public function test_it_should_compile_bool(): void
    {
        $tester = ExpressionTester::create();
        $tester
            ->evaluateExpression('true')
            ->assertValueIsTrue();
        $tester
            ->evaluateExpression('"true"')
            ->assertValueIsString("true");
    }

    public function test_it_should_compile_array(): void
    {
        $tester = ExpressionTester::create();
        $tester
            ->evaluateExpression('[12, 34.56, true, false, "string"]')
            ->assertHumanReadable(
                'Array(Integer(12), Float(34.56), Boolean(true), Boolean(false), String(string))'
            );
    }
}
