<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Functions\Math;

use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class DivideFunctionTest extends TestCase
{
    private function createTester(): ExpressionTester
    {
        return new ExpressionTester()
            ->withFunctions(new DivideFunction());
    }

    public function test_it_should_divide_by_number(): void
    {
        $this->createTester()
            ->evaluate('divide(3, 4)')
            ->assertValueIsFloat(.75);
    }

    public function test_it_should_not_allow_division_by_zero(): void
    {
        $this->createTester()
            ->evaluate('divide(34, 0)')
            ->assertValueIsFloat(0);
    }
}
