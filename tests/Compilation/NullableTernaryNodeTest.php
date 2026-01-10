<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Compilation;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Compilation\NotSupportedNode;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;

final class NullableTernaryNodeTest extends TestCase
{
    public function test_it_should_not_be_supported(): void
    {
        $this->expectException(NotSupportedNode::class);
        $this->expectExceptionMessage(
            'Node of type "Symfony\Component\ExpressionLanguage\Node\NullCoalesceNode" is not supported.'
        );
        ExpressionTester::create()
            ->evaluateExpression('12 ?? 4');
    }
}
