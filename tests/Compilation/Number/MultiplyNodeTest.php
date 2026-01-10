<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Number;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\Number\MultiplyNode;
use PHPUnit\Framework\TestCase;

final class MultiplyNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $left = self::createStub(ExpressionNode::class);
        $left
            ->method('compile')
            ->willReturn('left');
        $right = self::createStub(ExpressionNode::class);
        $right
            ->method('compile')
            ->willReturn('right');

        self::assertSame(
            'left * right',
            new MultiplyNode($left, $right)->compile([])
        );
    }
}
