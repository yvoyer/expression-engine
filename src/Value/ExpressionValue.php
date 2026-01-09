<?php declare(strict_types=1);

namespace Star\Component\ExpressionEngine\Value;

interface ExpressionValue
{
    public function isCastableToInteger(): bool;

    public function toInteger(): int;

    public function isCastableToFloat(): bool;

    public function toFloat(): float;

    public function isCastableToBoolean(): bool;

    public function toBoolean(): bool;

    public function isCastableToString(): bool;

    public function toString(): string;

    public function toHumanReadable(): string;
}
