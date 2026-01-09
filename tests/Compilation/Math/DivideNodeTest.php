<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Math;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\Math\DivideNode;
use PHPUnit\Framework\TestCase;

final class DivideNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $left = $this->createStub(ExpressionNode::class);
        $left
            ->method('compile')
            ->willReturn('left');
        $right = $this->createStub(ExpressionNode::class);
        $right
            ->method('compile')
            ->willReturn('right');

        self::assertSame(
            'divide(left, right)',
            new DivideNode($left, $right)->compile([])
        );
    }
}
