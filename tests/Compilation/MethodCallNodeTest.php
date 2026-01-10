<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\MethodArgument;
use Star\Component\ExpressionEngine\Compilation\MethodCallNode;
use PHPUnit\Framework\TestCase;

final class MethodCallNodeTest extends TestCase
{
    public function test_it_should_compile_with_no_arg(): void
    {
        self::assertSame(
            'var.method()',
            new MethodCallNode(
                'var',
                'method',
            )->compile([])
        );
    }

    public function test_it_should_compile_with_one_arg(): void
    {
        $arg = self::createStub(ExpressionNode::class);
        $arg->method('compile')
            ->willReturn('one');

        self::assertSame(
            'var.method(one)',
            new MethodCallNode(
                'var',
                'method',
                new MethodArgument(0, $arg),
            )->compile([])
        );
    }

    public function test_it_should_compile_with_many_args(): void
    {
        $one = self::createStub(ExpressionNode::class);
        $one->method('compile')
            ->willReturn('one');
        $two = self::createStub(ExpressionNode::class);
        $two->method('compile')
            ->willReturn('two');
        $three = self::createStub(ExpressionNode::class);
        $three->method('compile')
            ->willReturn('three');

        self::assertSame(
            'var.method(one, two, three)',
            new MethodCallNode(
                'var',
                'method',
                new MethodArgument(0, $one),
                new MethodArgument(1, $two),
                new MethodArgument(2, $three),
            )->compile([])
        );
    }
}
