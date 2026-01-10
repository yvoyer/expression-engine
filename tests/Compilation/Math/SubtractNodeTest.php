<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Math;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\Math\SubtractNode;
use PHPUnit\Framework\TestCase;

final class SubtractNodeTest extends TestCase
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
            'left - right',
            new SubtractNode($left, $right)->compile([])
        );
    }
}
