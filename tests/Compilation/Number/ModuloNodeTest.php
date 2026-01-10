<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Number;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ModuloNodeTest extends TestCase
{
    public function test_it_should_support_modulo(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('5 % 2')
            ->assertValueIsInteger(1)
        ;
    }
}
