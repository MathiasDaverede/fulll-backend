<?php

namespace App\Domain\ValueObject;

final class FleetId
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(FleetId $fleetId): bool
    {
        return $this->value === $fleetId->getValue();
    }
}
