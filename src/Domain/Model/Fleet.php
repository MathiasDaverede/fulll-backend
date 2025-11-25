<?php

namespace App\Domain\Model;

use App\App\Exception\VehicleAlreadyParkedException;
use App\App\Exception\VehicleAlreadyRegisteredException;
use App\App\Exception\VehicleNotRegisteredException;
use App\Domain\ValueObject\FleetId;
use App\Domain\ValueObject\Location;
use App\Domain\ValueObject\VehicleId;

class Fleet
{
    /** @var VehicleId[] */
    private array $registeredVehicles = [];

    /** @var Location[] */
    private array $vehicleLocations = [];

    public function __construct(private FleetId $fleetId) {}

    public function getFleetId(): FleetId
    {
        return $this->fleetId;
    }

    public function registerVehicle(VehicleId $vehicleId): void
    {
        if ($this->isVehicleRegistered($vehicleId)) {
            throw new VehicleAlreadyRegisteredException($this->fleetId, $vehicleId);
        }

        $this->registeredVehicles[] = $vehicleId;
    }

    public function isVehicleRegistered(VehicleId $vehicleId): bool
    {
        foreach ($this->registeredVehicles as $registeredId) {
            if ($registeredId->equals($vehicleId)) {
                return true;
            }
        }
        
        return false;
    }

    public function parkVehicle(VehicleId $vehicleId, Location $newLocation): void
    {
        if (!$this->isVehicleRegistered($vehicleId)) {
            throw new VehicleNotRegisteredException($this->fleetId, $vehicleId);
        }

        $currentLocation = $this->vehicleLocations[$vehicleId->getValue()] ?? null;

        if (!is_null($currentLocation) && $currentLocation->equals($newLocation)) {
             throw new VehicleAlreadyParkedException($vehicleId);
        }

        $this->vehicleLocations[$vehicleId->getValue()] = $newLocation;
    }

    public function getVehicleLocation(VehicleId $vehicleId): ?Location
    {
        return $this->vehicleLocations[$vehicleId->getValue()] ?? null;
    }
}
