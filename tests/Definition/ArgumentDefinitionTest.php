<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Definition;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Typing\ExpressionValueType;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\NotSupportedValue;

final class ArgumentDefinitionTest extends TestCase
{
    public function test_it_should_create_argument(): void
    {
        $type = $this->createStub(ExpressionValueType::class);
        $type->method('isValidValue')
            ->willReturn(true);
        $argument = new ArgumentDefinition(
            'name',
            1,
            $type
        )->createArgument($value = $this->createStub(ExpressionValue::class));

        self::assertSame('name', $argument->getName());
        self::assertSame(1, $argument->getPosition());
        self::assertSame($value, $argument->getValue());
    }

    public function test_it_should_throw_exception_when_value_is_not_valid_for_type(): void
    {
        $definition = new ArgumentDefinition(
            'name',
            1,
            $this->createStub(ExpressionValueType::class)
        );

        $this->expectException(NotSupportedValue::class);
        $this->expectExceptionMessage('Value "" is not a supported valid value for type "".');
        $definition->createArgument($this->createStub(ExpressionValue::class));
    }
}
