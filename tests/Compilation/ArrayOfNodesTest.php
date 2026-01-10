<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ArrayOfNodesTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('[1, 2, 3]')
            ->assertHumanReadable('Array(Integer(1), Integer(2), Integer(3))')
        ;
    }
}
