<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Comparator;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class NotNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('! true')
            ->assertValueIsFalse();
    }
}
