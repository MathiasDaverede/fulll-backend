<?php

namespace App\Command;

final class RegisterVehicleCommand
{
    public function __construct(
        private string $fleetId,
        private string $vehiclePlate
    ) {}

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }
}
