<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Comparator;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Compilation\InvalidSyntax;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class EqualNodeTest extends TestCase
{
    public function test_it_should_be_compiled(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('12 == 2')
            ->assertValueIsFalse();
    }

    public function test_it_should_always_be_strict_equal(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('12 == "12"')
            ->assertValueIsTrue();
    }

    public function test_strict_equal_should_not_be_valid(): void
    {
        $this->expectException(InvalidSyntax::class);
        $this->expectExceptionMessage('Operator of type "===" is not supported.');
        ExpressionTester::create()
            ->evaluateExpression('12 === "12"');
    }

    public function test_single_equal_should_be_valid(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('12 = "12"')
            ->assertValueIsTrue();
    }
}
