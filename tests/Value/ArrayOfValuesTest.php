<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Tests\Value;

use PHPUnit\Framework\TestCase;
use Star\Component\ExpressionEngine\Definition\ArgumentDefinition;
use Star\Component\ExpressionEngine\Functions\Stub\CallableFunction;
use Star\Component\ExpressionEngine\Tests\ExpressionTester;
use Star\Component\ExpressionEngine\Typing\IntegerType;
use Star\Component\ExpressionEngine\Value\ArrayOfValues;
use Star\Component\ExpressionEngine\Value\BooleanValue;
use Star\Component\ExpressionEngine\Value\ExpressionValue;
use Star\Component\ExpressionEngine\Value\FloatValue;
use Star\Component\ExpressionEngine\Value\IntegerValue;
use Star\Component\ExpressionEngine\Value\NotSupportedValue;
use Star\Component\ExpressionEngine\Value\StringValue;

final class ArrayOfValuesTest extends TestCase
{
    public function test_allow_empty(): void
    {
        $values = new ArrayOfValues();
        self::assertFalse($values->isCastableToInteger());
        self::assertFalse($values->isCastableToFloat());
        self::assertFalse($values->isCastableToBoolean());
        self::assertFalse($values->isCastableToString());
        self::assertSame('Array()', $values->toHumanReadable());
        self::assertSame('[]', $values->toCompiledString());
    }

    public function test_allow_one_element(): void
    {
        $values = new ArrayOfValues(
            IntegerValue::fromInteger(12),
        );
        self::assertFalse($values->isCastableToInteger());
        self::assertFalse($values->isCastableToFloat());
        self::assertFalse($values->isCastableToBoolean());
        self::assertFalse($values->isCastableToString());
        self::assertSame('Array(Integer(12))', $values->toHumanReadable());
        self::assertSame('[12]', $values->toCompiledString());
    }

    public function test_allow_many_elements(): void
    {
        $values = new ArrayOfValues(
            StringValue::fromString('string'),
            FloatValue::fromFloat(12.23),
            BooleanValue::fromBoolean(true),
        );
        self::assertFalse($values->isCastableToInteger());
        self::assertFalse($values->isCastableToFloat());
        self::assertFalse($values->isCastableToBoolean());
        self::assertFalse($values->isCastableToString());
        self::assertSame(
            'Array(String(string), Float(12.23), Boolean(true))',
            $values->toHumanReadable()
        );
        self::assertSame('["string", 12.23, true]', $values->toCompiledString());
    }

    public function test_it_should_not_allow_to_use_array_as_argument(): void
    {
        $tester = ExpressionTester::create()
            ->withFunctions(
                CallableFunction::withArg(
                    'fnc',
                    function () {
                    },
                    new ArgumentDefinition('name', 0, new IntegerType())
                )
            );
        $this->expectException(NotSupportedValue::class);
        $this->expectExceptionMessage('Value "Array(Integer(1))" is not a supported valid value for type "Integer".');
        $tester->evaluateExpression('fnc(var)', ['var' => [1]]);
    }
}
