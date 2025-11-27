<?php

namespace App\Command;

final class ParkVehicleCommand
{
    public function __construct(
        private string $fleetId,
        private string $vehiclePlate,
        private float $latitude,
        private float $longitude,
        private ?float $altitude = null
    ) {}

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }
}
