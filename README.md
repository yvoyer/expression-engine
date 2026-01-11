# Expression Engine

Typed expression engine based on [Symfony ExpressionLanguage](https://symfony.com/doc/current/components/expression_language.html) that
 enforces **strict type validation** and **explicit definitions**.

This engine is designed to safely evaluate expressions written by non-programmers while preventing implicit type
 juggling and ambiguous behavior.

---

## Installation

```bash
composer install star/expression-engine
```

---

## Usage

This library adds **static typing** to:

- Variables
- Operators
- Functions arguments
- Return values

Expressions that violate type constraints will fail **before evaluation**.

Some PHP-centric concepts were removed to keep expressions readable and safe.

---

## Type Notation & Keywords

This document uses **explicit type notation** to describe allowed operands.

Data types are written between `{}` to describe the **allowed type of a value** and its name for reference.

- `{int}`: Integer
- `{float}`: Float
- `{number}`: Integer or Float
- `{string}`: String
- `{bool}`: Boolean
- `{scalar}`: One of `int`, `float`, `string`, `bool`
- `{structure}`: Typed object exposing properties and methods
- `{array}`: A collection of item of any other type

⚠️ Not that `{array}` and `{structure}` are **not** included in `scalar`.

Arguments are described using a **named notation**:

```
{argumentName: type}
```

Example:
```
{dividend: number} / {divisor: number}
```

---

## Mathematical & Logical Operations

### Arithmetic Operators

- `{left: number} + {right: number}`: Addition of two numbers.
- `{left: number} - {right: number}`: Subtraction of two numbers.
- `{left: number} * {right: number}`: Multiplication of two numbers.
- `{dividend: number} / {divisor: number}`: Division of dividend by divisor. ⚠️  **Division by zero returns `0`.**
- `{base: number} ** {exponent: number}`: Power operation.
- `{value: number} % {modulo: number}`: Remainder of a division.
- `{start: number} .. {end: number}`: Generates an inclusive numeric range.

---

### Comparison Operators

All comparison operators return a **boolean**.

- `{left: scalar} == {right: scalar}`: Strict equality (no type coercion).
- `{left: scalar} != {right: scalar}`: Strict inequality.
- `{left: number} > {right: number}`
- `{left: number} >= {right: number}`
- `{left: number} < {right: number}`
- `{left: number} <= {right: number}`
- `{value: scalar} in [ {scalar} ]`: Checks if a value exists in an array.
- `{value: scalar} not in [ {scalar} ]`: Negated membership check.

---

### Boolean Operators

Logical AND:
- `{left: bool} && {right: bool}`
- `{left: bool} and {right: bool}`  

Logical OR:
- `{left: bool} || {right: bool}`
- `{left: bool} or {right: bool}`  

Boolean negation:
- `!{value: bool}`
- `not {value: bool}`  

---

### String Operators

- `{left: string} ~ {right: string}`: Concatenates two strings.
- `{haystack: string} contains {needle: string}`: Checks if a string contains another string.
- `{string: string} starts with {prefix: string}`: Checks if a string starts with a prefix.
- `{string: string} ends with {suffix: string}`: Checks if a string ends with a suffix.

---

### Bitwise Operators (Integers only)

- `{left: int} ^ {right: int}`: XOR
- `{left: int} | {right: int}`: OR
- `{left: int} & {right: int}`: AND
- `{value: int} >> {shift: int}`: Right shift
- `{value: int} << {shift: int}`: Left shift
- `~ {value: int}`: Bitwise NOT

---

### Conditional Expression

- `{condition: bool} ? {ifTrue: scalar} : {ifFalse: scalar}`: Ternary operator. Both branches must return compatible types.

---

### Structures, Arrays & Access

- `{structure}.property`: Access a typed property.
- `{array}[{index: int}]`: Access an array element by index.
- `{structure}.method()`: Call a typed method.

Square brackets define an **array of values**:

- `[ {number} ]`: Array of numbers
- `[ {scalar} ]`: Array of scalar values

Arrays must be homogeneous unless explicitly defined otherwise.
Array elements can be accessed using their integer index. Given `array` is an array, we can access the 4th element (Zero-index) `array[3]`.

---

## Supported Data Types

- **Integer (`int`)**: Range: `-9223372036854775808` to `9223372036854775807` *(Unlimited precision planned in the future)*
- **Float (`float`)**: Decimal numbers (e.g. `123.45`)
- **String (`string`)**: Text values
- **Boolean (`bool`)**: `true` or `false`
- **Scalar (`scalar`)**: One of `int`, `float`, `string`, `bool`
- **Structure (`structure`)**: Typed object with defined properties and methods.

---

## Functions

Functions must be:
- Explicitly registered
- Strictly typed
- Validated at parse time

Example:
```
max({a: number}, {b: number})
```

Calling a function with invalid argument types or count will raise a validation error.

### Custom function

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
---

## Limits & Guarantees

- ❌ No implicit type casting
- ❌ No dynamic typing
- ❌ No PHP-specific side effects
- ❌ No arrays or structures in `scalar`
- ✅ Deterministic evaluation
- ✅ Safe for user-provided expressions
- ✅ Early validation errors

---

## Design Goals

- Human-readable expressions
- Safe execution
- Early failure on invalid input
- Zero type ambiguity

This engine is ideal for **game rules**, **configuration systems**, and **domain-specific logic**.
