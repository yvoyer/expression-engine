<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\ArgumentNode;
use Star\Component\ExpressionEngine\Compilation\ExpressionNode;
use Star\Component\ExpressionEngine\Compilation\FunctionNode;
use PHPUnit\Framework\TestCase;

final class FunctionNodeTest extends TestCase
{
    public function test_it_should_compile_without_arg(): void
    {
        $arg = self::createStub(ExpressionNode::class);
        $arg->method('compile')
            ->willReturn('arg');

        self::assertSame(
            'fnc()',
            new FunctionNode('fnc')->compile([])
        );
    }

    public function test_it_should_compile_with_one_arg(): void
    {
        $arg = self::createStub(ExpressionNode::class);
        $arg->method('compile')
            ->willReturn('arg');

        self::assertSame(
            'fnc(arg)',
            new FunctionNode(
                'fnc',
                new ArgumentNode($arg),
            )->compile([])
        );
    }

    public function test_it_should_compile_with_three_args(): void
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
            'fnc(one, two, three)',
            new FunctionNode(
                'fnc',
                new ArgumentNode($one),
                new ArgumentNode($two),
                new ArgumentNode($three),
            )->compile([])
        );
    }
}
