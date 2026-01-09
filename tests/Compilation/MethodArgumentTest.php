<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\MethodArgument;
use PHPUnit\Framework\TestCase;

final class MethodArgumentTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $node = $this->createStub(ExpressionNode::class);
        $node->method('compile')
            ->willReturn('value');

        self::assertSame(
            'value',
            new MethodArgument(0, $node)->compile([])
        );
    }
}
