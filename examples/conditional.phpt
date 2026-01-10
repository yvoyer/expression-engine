--TEST--
Expression can do math operations
--FILE--
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Star\Component\ExpressionEngine\ExpressionRuntime;
use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;

$runtime = ExpressionRuntime::create();
$runtime->registerFunction(new DivideFunction()); // required to avoid division by zero error

$expression = '(age >= 18) ? "Adult": "Child"';

print_r([
'is child' => $runtime->evaluate($expression, ['age' => 17])->toString(),
'is adult' => $runtime->evaluate($expression, ['age' => 18])->toString(),
]);
?>
--EXPECT--
Array
(
    [math] => 10
    [division by zero] => 0
)
