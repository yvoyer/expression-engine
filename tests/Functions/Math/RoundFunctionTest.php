<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Functions\Math;

use Star\Component\ExpressionEngine\Functions\Math\RoundFunction;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class RoundFunctionTest extends TestCase
{
    public function test_it_should_round_number(): void
    {
        new ExpressionTester()
            ->withFunctions(new RoundFunction())
            ->evaluateExpression('round(2.6)')
            ->assertValueIsInteger(3);
    }

    public function test_it_should_compile_function_to_valid_value(): void
    {
        self::assertSame(
            '(int) round(2.3)',
            new ExpressionTester()
                ->withFunctions(new RoundFunction())
                ->compileExpression('round(2.3)')
        );
    }
}
