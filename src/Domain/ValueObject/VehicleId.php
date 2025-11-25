<?php

namespace App\Domain\ValueObject;

final class VehicleId
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(VehicleId $vehicleId): bool
    {
        return $this->value === $vehicleId->getValue();
    }
}
