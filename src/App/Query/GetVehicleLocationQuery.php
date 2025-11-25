<?php

namespace App\App\Query;

final class GetVehicleLocationQuery
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
