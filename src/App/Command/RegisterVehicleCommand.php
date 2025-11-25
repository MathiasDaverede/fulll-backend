<?php

namespace App\App\Command;

final class RegisterVehicleCommand
{
    public function __construct(
        private string $fleetId,
        private string $vehiclePlateNumber
    ) {}

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getVehiclePlateNumber(): string
    {
        return $this->vehiclePlateNumber;
    }
}
