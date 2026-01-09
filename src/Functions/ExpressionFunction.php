<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Functions;

use Star\Component\ExpressionEngine\Definition\FunctionDefinition;

interface ExpressionFunction
{
    public function createDefinition(): FunctionDefinition;
}
