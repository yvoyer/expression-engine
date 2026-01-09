<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation\Math;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\Math\OperatorNode;
use PHPUnit\Framework\TestCase;

final class OperatorNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $node = $this->createStub(ExpressionNode::class);
        $node->method('compile')
            ->willReturn('value');
        self::assertSame(
            '- value',
            new OperatorNode('-', $node)->compile([])
        );
    }
}
