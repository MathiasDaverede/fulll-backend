<?php

namespace App\Query;

final class GetFleetVehiclesQuery
{
    public function __construct(private string $fleetId) {}

    public function getFleetId(): string
    {
        return $this->fleetId;
    }
}
