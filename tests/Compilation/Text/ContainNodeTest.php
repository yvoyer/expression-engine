<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Text;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ContainNodeTest extends TestCase
{
    public function test_it_should_support_string_contains(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('"string" contains "i"')
            ->assertValueIsTrue()
        ;
    }
}
