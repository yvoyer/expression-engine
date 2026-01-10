--TEST--
Expression can do math operations
--FILE--
<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Star\Component\ExpressionEngine\ExpressionRuntime;
use Star\Component\ExpressionEngine\Functions\Math\DivideFunction;

$runtime = ExpressionRuntime::create();
$runtime->registerFunction(new DivideFunction()); // required to avoid division by zero error

print_r([
'math' => $runtime->evaluate('(10*2)-(10+2/4)')->toInteger(),
'division by zero' => $runtime->evaluate('10/0')->toInteger(),
]);
?>
--EXPECT--
Array
(
    [math] => 10
    [division by zero] => 0
)
