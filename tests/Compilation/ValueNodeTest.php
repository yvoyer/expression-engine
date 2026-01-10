<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\ValueNode;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Value\ExpressionValue;

final class ValueNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $value = self::createStub(ExpressionValue::class);
        $value->method('toString')
            ->willReturn('value');

        self::assertSame(
            'value',
            new ValueNode($value)->compile([])
        );
    }
}
