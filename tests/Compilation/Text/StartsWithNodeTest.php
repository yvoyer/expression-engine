<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Text;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class StartsWithNodeTest extends TestCase
{
    public function test_it_should_support_string_begins_with(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('"string" starts with "i"')
            ->assertValueIsFalse()
        ;
    }
}
