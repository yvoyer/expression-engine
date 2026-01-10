<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Bitwise;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class NotBitNodeTest extends TestCase
{
    public function test_it_should_support_binary_or_inclusive(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('~ 4')
            ->assertValueIsInteger(-5)
        ;
    }
}
