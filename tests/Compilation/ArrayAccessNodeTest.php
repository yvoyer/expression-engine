<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ArrayAccessNodeTest extends TestCase
{
    public function test_it_should_support_access_to_array_index(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('var[1]', ['var' => [1 => 'Joe']])
            ->assertValueIsString('Joe')
        ;
    }
}
