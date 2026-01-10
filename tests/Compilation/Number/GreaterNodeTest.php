<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Number;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class GreaterNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('3>4')
            ->assertValueIsFalse();
    }

    public function test_it_should_compile_with_different_types(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('4>"3"')
            ->assertValueIsTrue();
    }
}
