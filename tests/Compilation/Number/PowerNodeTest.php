<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Number;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class PowerNodeTest extends TestCase
{
    public function test_it_should_support_power_of_number(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('2 ** 3')
            ->assertValueIsInteger(8)
        ;
    }
}
