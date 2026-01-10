<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Definition;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Definition\ArgumentNotFound;
use Star\Component\ExpressionEngine\Definition\FunctionArgument;
use Star\Component\ExpressionEngine\Definition\FunctionArguments;
use Star\Component\ExpressionEngine\Definition\InvalidArgumentCount;
use Star\Component\ExpressionEngine\Value\ExpressionValue;

final class FunctionArgumentsTest extends TestCase
{
    public function test_it_should_return_the_value_by_name(): void
    {
        $arguments = FunctionArguments::fromCollection(
            'fnc',
            new FunctionArgument(
                'name',
                0,
                $value = self::createStub(ExpressionValue::class),
            )
        );
        self::assertSame(
            $value,
            $arguments->getValueByName('name')
        );
    }

    public function test_it_should_return_the_value_by_position(): void
    {
        $arguments = FunctionArguments::fromCollection(
            'fnc',
            new FunctionArgument(
                'name',
                0,
                $value = self::createStub(ExpressionValue::class)
            )
        );
        self::assertSame(
            $value,
            $arguments->getValueByPosition(0)
        );
    }

    public function test_it_should_throw_exception_when_arg_not_found_by_name(): void
    {
        $arguments = FunctionArguments::fromCollection('fnc');

        $this->expectException(ArgumentNotFound::class);
        $this->expectExceptionMessage('Argument "name" of function "FNC()" could not be found. Was it defined ?');
        $arguments->getValueByName('name');
    }

    public function test_it_should_throw_exception_when_arg_not_found_by_position(): void
    {
        $arguments = FunctionArguments::fromCollection('fnc');

        $this->expectException(ArgumentNotFound::class);
        $this->expectExceptionMessage(
            'Argument of function "FNC()" could not be found at position "1". Was it defined ?'
        );
        $arguments->getValueByPosition(1);
    }

    public function test_it_should_throw_exception_when_argument_count_not_as_expected(): void
    {
        $arguments = FunctionArguments::fromCollection('fnc');

        $this->expectException(InvalidArgumentCount::class);
        $this->expectExceptionMessage('Argument count for function "FNC()" was expected to be exactly "3", "0" given.');
        $arguments->assertArgumentCount(3);
    }

    public function test_it_should_throw_exception_when_argument_count_by_name_and_position_do_not_match(): void
    {
        $arguments = FunctionArguments::fromCollection(
            'fnc',
            new FunctionArgument('one', 1, self::createStub(ExpressionValue::class)),
            new FunctionArgument('two', 1, self::createStub(ExpressionValue::class)),
        );

        $this->expectException(InvalidArgumentCount::class);
        $this->expectExceptionMessage(
            'Argument count is inconsistent by name (2) and by position (1) for function "FNC()".'
        );
        $arguments->assertArgumentCount(3);
    }
}
