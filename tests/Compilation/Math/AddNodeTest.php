<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Math;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\Math\AddNode;
use PHPUnit\Framework\TestCase;

final class AddNodeTest extends TestCase
{
    public function test_it_should_compile_to_addition(): void
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
            'left + right',
            new AddNode($left, $right)->compile([])
        );
    }
}
