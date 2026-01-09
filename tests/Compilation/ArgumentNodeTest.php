<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use Star\Component\ExpressionEngine\Compilation\ArgumentNode;
use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Compilation\ExpressionNode;

final class ArgumentNodeTest extends TestCase
{
    public function test_it_should_compile(): void
    {
        $value = $this->createStub(ExpressionNode::class);
        $value
            ->method('compile')
            ->willReturn('value');
        self::assertSame(
            'value',
            new ArgumentNode($value)->compile([])
        );
    }
}
