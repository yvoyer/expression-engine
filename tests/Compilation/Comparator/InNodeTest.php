<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Comparator;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class InNodeTest extends TestCase
{
    public function test_it_should_compile_with_no_value(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('12 in []')
            ->assertValueIsFalse();
    }

    public function test_it_should_compile_with_one_value(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('12 in ["12"]')
            ->assertValueIsTrue();
    }

    public function test_it_should_compile_with_many_values(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('32 in ["12", 2, 54]')
            ->assertValueIsFalse();
    }
}
