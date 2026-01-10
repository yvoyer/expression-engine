<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Bitwise;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ShiftRightNodeTest extends TestCase
{
    public function test_it_should_support_binary_shift_right(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('8 >> 1')
            ->assertValueIsInteger(4)
        ;
    }
}
