<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Text;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class ConcatNodeTest extends TestCase
{
    public function test_it_should_support_concat_of_string(): void
    {
        ExpressionTester::create()
            ->evaluateExpression('"left" ~ "right"')
            ->assertValueIsString('leftright')
        ;
    }
}
