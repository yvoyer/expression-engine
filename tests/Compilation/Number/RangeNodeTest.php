<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Number;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class RangeNodeTest extends TestCase
{
    public function test_it_should_support_range_of_number(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('2 .. 6')
            ->assertHumanReadable('Array(Integer(2), Integer(3), Integer(4), Integer(5), Integer(6))')
        ;
    }
}
