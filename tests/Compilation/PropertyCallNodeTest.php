<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\PropertyCallNode;
use PHPUnit\Framework\TestCase;

final class PropertyCallNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        self::assertSame(
            'var.prop',
            new PropertyCallNode('var', 'prop')->compile([])
        );
    }
}
