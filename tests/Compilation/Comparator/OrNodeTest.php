<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Comparator;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class OrNodeTest extends TestCase
{
    public function test_it_should_allow_or_operator(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('true || false')
            ->assertValueIsTrue();
    }

    public function test_it_should_allow_operator_as_word(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('true or false')
            ->assertValueIsTrue();
    }
}
