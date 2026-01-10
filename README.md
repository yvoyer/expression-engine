# expression-engine

Expression engine based of Symfony Expression that allow type validation and strict definitions.

Engine to run expression and typed function in a strict way.

## Installation

```
composer install star/expression-engine
```

## Usage

This library uses the [SymfonyExpression](https://symfony.com/doc/current/components/expression_language.html) and
 adds typing to all the variable and functions. This allow us to ensure that provided values are strict without type juggling.

We removed some usage to only use concept that non-programmer could understand.

### Mathematical operations

Operators:
* `+`: Addition of numbers. ie. `12 + var + 4` (Given `var = 3`, the result would be `19`).
* `-`: Subtraction of numbers. ie. `20 - var` (Given `var = 3`, the result would be `17`).
* `*`: Multiplication of numbers. ie. `12 * var` (Given `var = 3`, the result would be `36`).
* `/`: Division of numbers. ie. `12 / var` (Given `var = 3`, the result would be `4`).

*Note:* Division by zero would result in `0`.

* `!` or `not`: Inverse operator will reverse a condition. ie. `!var` (Given `var = true`, the result would be `false`).

### Types of values

* Integer: A number without decimal included between `-9223372036854775808` and `9223372036854775807`.
 (*Note* we'll expand this type in the future to have unlimited size.)
* Float: A number with decimal points. ie. `123.345`. 
* String: A string of characters like words.
* Boolean: A `true` of `false` value.
* Structure: The structure is a complex object that contains properties and functions. ie. `person.age` or `person.increaseAge(2)`.

### Conditions

* `&&`: And operator.
* `||`: Or operator
* `<`: Less than
* `>`: Greater than
* `<=`: Less equal than
* `>=`: Greater equal than
* `==`: Equal
* `!=`: Not equal
* `===`: Strict equal
* `!==`: Strict not equal

### Function 



#### Custom function

```php
use Star\Component\ExpressionEngine\ExpressionRuntime;

$engine = ExpressionRuntime::create();
$engine->registerFunction(
    new class() implements ExpressionFunction {
        public function createDefinition(): FunctionDefinition
        {
            return new FunctionDefinition(
                'ageMultiplier',
                function ($age): string {
                    // The compiled string evaluatable for caching
                    return sprintf('$age * %s', $multiplier);
                },
                function (
                    FunctionContext $context,
                    FunctionArguments $arguments,
                ): int {
                    // The code to execute 
                    $arguments->assertArgumentCount(1);
                    $multiplier = $arguments->getValueByName('multiplier')->toInteger();

                    return $context->getIntegerValue('age') * $multiplier;
                },
            )->addNamedArgument(
                new ArgumentDefinition('multiplier', 0, new IntegerType())
            );
        }
    }
);
echo $engine->evaluate(
    'ageMultiplier(4)',
    [
        'age' => 23,
    ]
)->toInteger(); // Output 92
echo $engine->compile('ageMultiplier(4)', ['age']); // $age * 4
```
