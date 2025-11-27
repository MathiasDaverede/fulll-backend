<?php

namespace Domain\ValueObject;

final class FleetId
{
    private function __construct(private string $value) {}

    public static function generate(?string $fleetId = null): self
    {
        if (is_null($fleetId)) {
            $fleetId = 'fleet_' . uniqid();
        }
        return new self($fleetId);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(FleetId $fleetId): bool
    {
        return $this->value === $fleetId->getValue();
    }
}
