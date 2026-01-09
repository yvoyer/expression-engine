<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Functions\Math;

use Star\Component\ExpressionEngine\Functions\Math\RoundFunction;
use PHPUnit\Framework\TestCase;

final class RoundFunctionTest extends TestCase
{
    public function test_it_should_round_number(): void
    {
        $fn = new RoundFunction()->createDefinition()->createFunction()->getEvaluator();

        self::assertSame(3, $fn([], 2.6));
    }
}
