<?php

namespace Domain\ValueObject;

final class VehiclePlate
{
    public function __construct(private string $value) {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(VehiclePlate $vehiclePlate): bool
    {
        return $this->value === $vehiclePlate->getValue();
    }
}
