<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Definition;

use Assert\Assertion;

final readonly class FunctionContext
{
    /**
     * @var array<string, string|int|float|bool|object>
     */
    private array $context;

    /**
     * @param array<string, string|int|float|bool|object> $context
     */
    public function __construct(
        private string $function,
        array $context,
    ) {
        $this->context = $context;
    }

    private function getValue(string $variable): bool|float|int|string|object
    {
        if (!array_key_exists($variable, $this->context)) {
            throw new InvalidFunctionContextValue(
                sprintf(
                    'The variable "%s" do not exists for function "%s()", available variables "%s".',
                    $variable,
                    $this->function,
                    implode(', ', array_keys($this->context))
                )
            );
        }

        return $this->context[$variable];
    }

    public function getStringValue(string $variable): string
    {
        $value = $this->getValue($variable);
        Assertion::scalar($value);

        return (string) $value;
    }

    public function getIntegerValue(string $variable): int
    {
        $value = $this->getValue($variable);
        Assertion::integerish($value);

        return (int) $value;
    }

    public function getNumberValue(string $variable): float
    {
        $value = $this->getValue($variable);
        Assertion::numeric($value);

        return (float) $value;
    }

    public function getBooleanValue(string $variable): bool
    {
        $value = $this->getValue($variable);
        Assertion::inArray($value, [1, 0, '1', '0', true, false]);

        return (bool) $value;
    }
}
