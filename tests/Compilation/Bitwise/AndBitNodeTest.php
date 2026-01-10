<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Bitwise;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class AndBitNodeTest extends TestCase
{
    public function test_it_should_support_binary_and(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('2 & 3')
            ->assertValueIsInteger(2)
        ;
    }
}
