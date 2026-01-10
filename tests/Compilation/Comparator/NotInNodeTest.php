<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Comparator;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class NotInNodeTest extends TestCase
{
    public function test_compile(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('32 not in [12, 34]')
            ->assertValueIsTrue();
    }
}
