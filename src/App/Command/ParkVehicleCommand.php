<?php

namespace App\App\Command;

final class ParkVehicleCommand
{
    public function __construct(
        private string $fleetId,
        private string $vehiclePlateNumber,
        private float $latitude,
        private float $longitude,
        private ?float $altitude = null
    ) {}

    public function getFleetId(): string
    {
        return $this->fleetId;
    }

    public function getVehiclePlateNumber(): string
    {
        return $this->vehiclePlateNumber;
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
