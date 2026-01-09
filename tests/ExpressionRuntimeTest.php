<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests;

use Star\Component\ExpressionEngine\Functions\Math\RoundFunction;
use PHPUnit\Framework\TestCase;

final class ExpressionRuntimeTest extends TestCase
{
    private function createTester(): ExpressionTester
    {
        return new ExpressionTester();
    }

    public function test_it_should_perform_math_operation(): void
    {
        $this->createTester()
            ->evaluate('2+3')
            ->assertValueIsInteger(5);
    }

    public function test_it_should_evaluate_function(): void
    {
        $this->createTester()
            ->withFunctions(new RoundFunction())
            ->evaluate('round(5 / 2)')
            ->assertValueIsInteger(3);
    }
}
