<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Bitwise;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class XOrNodeTest extends TestCase
{
    public function test_it_should_support_binary_or_exclusive(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('4 ^ 2')
            ->assertValueIsInteger(6)
        ;
    }
}
