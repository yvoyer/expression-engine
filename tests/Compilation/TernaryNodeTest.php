<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class TernaryNodeTest extends TestCase
{
    public function test_ternary_operator(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('true ? 2: 4')
            ->assertValueIsInteger(2);
    }
}
